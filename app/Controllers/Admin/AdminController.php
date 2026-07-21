<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EnquiryModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Images\Exceptions\ImageException;
use CodeIgniter\Model;

/**
 * Shared plumbing for the admin screens: layout data, image uploads, and the
 * HX-Trigger conventions the admin JS listens for.
 */
abstract class AdminController extends BaseController
{
    /** Largest edge, in pixels, that an uploaded image is resized down to. */
    protected const MAX_IMAGE_EDGE = 1600;

    protected const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    /** Merges the data every admin view needs into the payload. */
    protected function layout(array $data = []): array
    {
        return array_merge([
            'newEnquiries' => (new EnquiryModel())->countNew(),
        ], $data);
    }

    /**
     * Handles an uploaded image: validates, resizes if oversized, stores it
     * under public/uploads/{$folder}/ and returns the path to save in the DB.
     *
     * Returns null when no file was submitted, so callers can leave the
     * existing image in place.
     *
     * @throws \RuntimeException when the file is present but unusable.
     */
    protected function handleUpload(string $field, string $folder): ?string
    {
        $file = $this->request->getFile($field);

        if ($file === null || ! $file->isValid() || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (! in_array($file->getMimeType(), self::ALLOWED_IMAGE_TYPES, true)) {
            throw new \RuntimeException('That file type is not supported. Use JPEG, PNG, WebP or GIF.');
        }

        if ($file->getSize() > 8 * 1024 * 1024) {
            throw new \RuntimeException('That image is larger than 8 MB. Please compress it first.');
        }

        $dir = FCPATH . 'uploads/' . $folder;
        if (! is_dir($dir) && ! @mkdir($dir, 0777, true) && ! is_dir($dir)) {
            throw new \RuntimeException('Could not create the upload folder.');
        }

        $name = $file->getRandomName();
        $file->move($dir, $name);

        $fullPath = $dir . DIRECTORY_SEPARATOR . $name;

        // Scale oversized photographs down; anything already small is left alone.
        try {
            $size = @getimagesize($fullPath);
            if ($size !== false && max($size[0], $size[1]) > self::MAX_IMAGE_EDGE) {
                service('image')
                    ->withFile($fullPath)
                    ->resize(
                        self::MAX_IMAGE_EDGE,
                        self::MAX_IMAGE_EDGE,
                        true,
                        $size[0] >= $size[1] ? 'width' : 'height'
                    )
                    ->save($fullPath, 82);
            }
        } catch (ImageException $e) {
            // A resize failure is not worth losing the upload over — keep the
            // original and note it.
            log_message('warning', 'Image resize failed for {path}: {msg}', [
                'path' => $fullPath,
                'msg'  => $e->getMessage(),
            ]);
        }

        return 'uploads/' . $folder . '/' . $name;
    }

    /** Deletes a previously uploaded file. Ignores external URLs and misses. */
    protected function deleteUpload(?string $path): void
    {
        $path = trim((string) $path);

        if ($path === '' || str_contains($path, '://') || ! str_starts_with($path, 'uploads/')) {
            return;
        }

        $full = FCPATH . $path;
        if (is_file($full)) {
            @unlink($full);
        }
    }

    /**
     * Updates a row, reporting failures rather than swallowing them.
     *
     * The `id` is merged into the payload because CodeIgniter resolves the
     * `{id}` placeholder in rules like `is_unique[table.slug,id,{id}]` from the
     * data array, not from the primary key argument. Without it a row cannot be
     * saved without changing its own slug. `id` is not in any model's
     * $allowedFields, so it never reaches the UPDATE statement.
     *
     * @return bool true when the row was saved.
     */
    protected function saveRow(Model $model, int $id, array $data): bool
    {
        if ($model->update($id, $data + ['id' => $id])) {
            return true;
        }

        $this->toast(implode(' ', $model->errors()) ?: 'Could not save those changes.', 'error');

        return false;
    }

    /**
     * Inserts a row, reporting validation failures.
     *
     * @return int|null the new id, or null when validation failed.
     */
    protected function insertRow(Model $model, array $data): ?int
    {
        if ($model->insert($data) === false) {
            $this->toast(implode(' ', $model->errors()) ?: 'Could not save that.', 'error');

            return null;
        }

        return (int) $model->getInsertID();
    }

    /** Adds the HX-Trigger the admin JS turns into a toast. */
    protected function toast(string $message, string $type = 'success'): ResponseInterface
    {
        return $this->response->setHeader('HX-Trigger', json_encode([
            'bba:toast' => ['message' => $message, 'type' => $type],
        ]));
    }

    /** Toast plus close the shared modal. */
    protected function toastAndClose(string $message, string $type = 'success'): ResponseInterface
    {
        return $this->response->setHeader('HX-Trigger', json_encode([
            'bba:toast'        => ['message' => $message, 'type' => $type],
            'bba:close-modal'  => true,
        ]));
    }

    /**
     * Makes a URL-safe slug, appending -2, -3 … until it is unique in $table.
     * $ignoreId lets a row keep its own slug when being updated.
     */
    protected function uniqueSlug(string $source, string $table, ?int $ignoreId = null): string
    {
        $base = trim(preg_replace('/-+/', '-', preg_replace('/[^a-z0-9]+/', '-', strtolower(
            str_replace('&', ' and ', $source)
        ))), '-');

        if ($base === '') {
            $base = 'item';
        }

        $db   = db_connect();
        $slug = $base;
        $n    = 1;

        while (true) {
            $builder = $db->table($table)->where('slug', $slug);
            if ($ignoreId !== null) {
                $builder->where('id !=', $ignoreId);
            }

            if ($builder->countAllResults() === 0) {
                return $slug;
            }

            $slug = $base . '-' . (++$n);
        }
    }
}
