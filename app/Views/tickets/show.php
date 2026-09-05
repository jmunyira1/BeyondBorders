<?php
/**
 * Public adventure ticket. Self-contained page (own head) so the PDF export
 * captures exactly the ticket. Branded MOROP GAA green + gold to match the
 * printed-ticket design, independent of the site's teal theme.
 *
 * @var array $ticket
 */
use App\Models\TicketModel;

$paid       = ($ticket['payment_status'] ?? '') === 'paid';
$includes   = TicketModel::decodeIncludes($ticket['includes'] ?? null);
$refCompact = str_replace('-', '', (string) ($ticket['ticket_ref'] ?? ''));
$hero       = $ticket['image'] ? media_url($ticket['image']) : 'https://picsum.photos/seed/moropadv/1000/520';
$ticketUrl  = base_url('ticket/' . ($ticket['token'] ?? ''));
$eventDate  = ! empty($ticket['event_date']) ? strtotime($ticket['event_date']) : null;
$bookedAt   = ! empty($ticket['paid_at']) ? strtotime($ticket['paid_at']) : (! empty($ticket['created_at']) ? strtotime($ticket['created_at']) : time());
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ticket <?= esc($ticket['ticket_ref'] ?? '') ?> — <?= esc(site('companyName')) ?></title>
<meta name="robots" content="noindex, nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Oswald:wght@500;600;700&family=Pacifico&family=Dancing+Script:wght@600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
  :root{
    --green:#1f4030; --green-2:#152e22; --green-line:#2f5a44;
    --gold:#d3a441; --gold-2:#b98a2f;
    --ink:#20302a; --muted:#7c8a83; --line:#e7ebe8; --panel:#f6f8f6;
    --paid:#2e7d32; --paper:#ffffff;
  }
  *{box-sizing:border-box}
  html,body{margin:0}
  body{
    font-family:"Inter",system-ui,sans-serif; color:var(--ink);
    background:#e9ece9; padding:24px 14px 40px; -webkit-font-smoothing:antialiased;
  }
  .tk-wrap{max-width:520px;margin:0 auto}
  .ticket{
    background:var(--paper); border-radius:22px; overflow:hidden;
    box-shadow:0 24px 60px rgba(20,40,30,.18);
  }
  /* Header */
  .tk-header{
    background:linear-gradient(135deg,var(--green),var(--green-2));
    color:#fff; padding:20px 22px; display:flex; justify-content:space-between; align-items:center; gap:14px;
  }
  .tk-brand{display:flex; align-items:center; gap:11px}
  .tk-mark{width:40px;height:40px;flex:0 0 auto;color:var(--gold)}
  .tk-brand-name{font-family:"Oswald",sans-serif; font-weight:700; font-size:22px; letter-spacing:.02em; line-height:1; color:#fff}
  .tk-brand-sub{font-size:8.5px; letter-spacing:.32em; color:rgba(255,255,255,.75); margin-top:3px}
  .tk-brand-tag{font-family:"Dancing Script",cursive; font-weight:700; font-size:14px; color:var(--gold); margin-top:2px}
  .tk-type{text-align:right; line-height:1.05}
  .tk-type span{display:block; font-family:"Oswald",sans-serif; font-weight:600; font-size:20px; letter-spacing:.18em; color:#fff}
  .tk-type strong{display:block; font-family:"Oswald",sans-serif; font-weight:700; font-size:20px; letter-spacing:.18em; color:var(--gold)}
  /* Hero */
  .tk-hero{position:relative; height:190px; margin:14px; border-radius:16px; overflow:hidden; background:#12251b}
  .tk-hero img{width:100%;height:100%;object-fit:cover;display:block}
  .tk-hero::after{content:"";position:absolute;inset:0;background:linear-gradient(90deg,rgba(15,35,25,.55),rgba(15,35,25,0) 55%)}
  .tk-hero-title{position:absolute; left:20px; bottom:16px; z-index:2}
  .tk-hero-main{display:block; font-family:"Oswald",sans-serif; font-weight:700; font-size:38px; line-height:.9; color:#eafff2; text-shadow:0 2px 10px rgba(0,0,0,.45); letter-spacing:.01em}
  .tk-hero-script{display:block; font-family:"Pacifico",cursive; font-size:34px; color:var(--gold); line-height:.9; margin-top:-4px; text-shadow:0 2px 10px rgba(0,0,0,.4)}
  /* Meta row */
  .tk-meta{display:flex; padding:6px 22px 16px; gap:14px}
  .tk-meta>div{display:flex; align-items:center; gap:10px; flex:1}
  .tk-meta i{font-size:20px; color:var(--green)}
  .tk-meta .k{font-weight:700; font-size:13.5px; letter-spacing:.02em}
  .tk-meta .s{font-size:11px; color:var(--muted); letter-spacing:.06em}
  .tk-meta .div{flex:0 0 1px; align-self:stretch; background:var(--line)}
  /* Body: details + qr */
  .tk-body{display:flex; gap:12px; padding:8px 16px 4px; border-top:1px solid var(--line)}
  .tk-details{flex:1; min-width:0; padding-top:12px}
  .tk-row{display:flex; align-items:center; margin-bottom:11px}
  .tk-row .lbl{flex:0 0 92px; font-size:10px; letter-spacing:.07em; color:var(--muted); font-weight:600; text-transform:uppercase}
  .tk-row .val{flex:1; min-width:0; font-weight:600; font-size:13px; word-break:break-word}
  .tk-paid{display:inline-flex; align-items:center; gap:5px; background:var(--paid); color:#fff; font-weight:700; font-size:11px; padding:3px 9px; border-radius:999px; white-space:nowrap}
  .tk-unpaid{background:#9aa3a0}
  .tk-qr{flex:0 0 106px; text-align:center; padding-top:14px}
  .tk-qr #qrcode{width:104px;height:104px;margin:0 auto;background:#fff}
  .tk-qr #qrcode img,.tk-qr #qrcode canvas{width:104px !important;height:104px !important}
  .tk-qr-ref{margin-top:6px; font-size:11px; letter-spacing:.08em; color:var(--muted); font-weight:600}
  /* Boxes */
  .tk-box{margin:14px 22px 0; border:1px solid var(--line); border-radius:14px; padding:14px 16px; background:var(--panel)}
  .tk-box-label{font-size:10.5px; letter-spacing:.12em; color:var(--muted); font-weight:700; text-transform:uppercase; margin-bottom:10px}
  .tk-incl{display:flex; justify-content:space-between; gap:8px; text-align:center}
  .tk-incl>div{flex:1}
  .tk-incl i{font-size:22px; color:var(--green)}
  .tk-incl .t{display:block; font-size:11px; margin-top:6px; color:var(--ink); line-height:1.25}
  .tk-pickup{display:flex; justify-content:space-between; align-items:center; gap:12px}
  .tk-pickup .place{font-weight:600; font-size:13.5px; margin-bottom:2px}
  .tk-pickup .time{font-size:11.5px; color:var(--muted); letter-spacing:.04em; font-weight:600}
  .tk-ty{text-align:right}
  .tk-ty .s{font-family:"Dancing Script",cursive; font-weight:700; font-size:26px; color:var(--green); line-height:1}
  .tk-ty .n{font-size:11px; color:var(--muted); margin-top:2px}
  /* Perforated stub */
  .tk-perf{position:relative; height:22px; margin-top:16px}
  .tk-perf::before{content:""; position:absolute; top:50%; left:16px; right:16px; border-top:2px dashed #cfd8d2}
  .tk-perf::after{content:""; position:absolute; top:50%; left:-11px; width:22px; height:22px; border-radius:50%; background:#e9ece9; transform:translateY(-50%)}
  .tk-perf .r{position:absolute; top:50%; right:-11px; width:22px; height:22px; border-radius:50%; background:#e9ece9; transform:translateY(-50%)}
  .tk-stub{background:linear-gradient(135deg,var(--green-2),var(--green)); color:var(--gold); text-align:center; padding:14px; font-weight:600; font-size:13.5px; letter-spacing:.02em}
  .tk-stub i{margin-right:7px}
  /* Actions + footer (not part of the exported ticket) */
  .tk-actions{display:flex; flex-wrap:wrap; gap:10px; justify-content:center; margin:22px auto 0; max-width:520px}
  .tk-btn{border:0; cursor:pointer; font:inherit; font-weight:600; font-size:14px; padding:11px 18px; border-radius:999px; display:inline-flex; align-items:center; gap:8px; text-decoration:none}
  .tk-btn-primary{background:var(--green); color:#fff}
  .tk-btn-wa{background:#25d366; color:#fff}
  .tk-btn-ghost{background:#fff; color:var(--green); border:1px solid var(--line)}
  .tk-note{max-width:520px; margin:14px auto 0; text-align:center; color:var(--muted); font-size:12.5px}
  @media print{
    body{background:#fff;padding:0}
    .tk-actions,.tk-note{display:none !important}
    .ticket{box-shadow:none}
  }
</style>
</head>
<body>
  <div class="tk-wrap">
    <div class="ticket" id="ticket">

      <div class="tk-header">
        <div class="tk-brand">
          <svg class="tk-mark" viewBox="0 0 48 48" fill="none" aria-hidden="true">
            <path d="M4 40 L18 14 L26 28 L32 18 L44 40 Z" fill="currentColor"/>
            <path d="M18 14 L26 28 L22 28 L18 21 L12 32 L8 32 Z" fill="#15351f" opacity=".35"/>
          </svg>
          <div>
            <div class="tk-brand-name">MOROP GAA</div>
            <div class="tk-brand-sub">TOURS &amp; ADVENTURES</div>
            <div class="tk-brand-tag">Explore. Experience. Enjoy.</div>
          </div>
        </div>
        <div class="tk-type">
          <span>ADVENTURE</span>
          <strong>TICKET</strong>
        </div>
      </div>

      <div class="tk-hero">
        <img src="<?= esc($hero, 'attr') ?>" alt="" crossorigin="anonymous">
        <div class="tk-hero-title">
          <span class="tk-hero-main"><?= esc(strtoupper($ticket['adventure_name'] ?? 'Adventure')) ?></span>
          <span class="tk-hero-script">Adventure</span>
        </div>
      </div>

      <div class="tk-meta">
        <div>
          <i class="bi bi-calendar-event"></i>
          <div>
            <div class="k"><?= $eventDate ? esc(strtoupper(date('jS M Y', $eventDate))) : 'DATE TBC' ?></div>
            <div class="s"><?= $eventDate ? esc(strtoupper(date('l', $eventDate))) : '' ?></div>
          </div>
        </div>
        <div class="div"></div>
        <div>
          <i class="bi bi-geo-alt"></i>
          <div>
            <div class="k"><?= esc(strtoupper($ticket['event_location'] ?? 'KENYA')) ?></div>
            <div class="s">KENYA</div>
          </div>
        </div>
      </div>

      <div class="tk-body">
        <div class="tk-details">
          <div class="tk-row"><span class="lbl">Guest name</span><span class="val"><?= esc($ticket['guest_name'] ?? '') ?></span></div>
          <div class="tk-row"><span class="lbl">Ticket ID</span><span class="val"><?= esc($ticket['ticket_ref'] ?? '') ?></span></div>
          <div class="tk-row"><span class="lbl">Ticket type</span><span class="val"><?= esc($ticket['ticket_type'] ?? 'General Admission') ?></span></div>
          <div class="tk-row"><span class="lbl">Amount paid</span><span class="val"><?= esc(money($ticket['amount'] ?? 0, $ticket['currency'] ?? 'KES')) ?></span></div>
          <div class="tk-row"><span class="lbl">Payment status</span><span class="val">
            <span class="tk-paid<?= $paid ? '' : ' tk-unpaid' ?>"><i class="bi bi-<?= $paid ? 'check-lg' : 'hourglass-split' ?>"></i><?= $paid ? 'PAID' : 'PENDING' ?></span>
          </span></div>
          <div class="tk-row"><span class="lbl">Booking date</span><span class="val"><?= esc(date('jS M Y | h:i A', $bookedAt)) ?></span></div>
        </div>
        <div class="tk-qr">
          <div id="qrcode" data-url="<?= esc($ticketUrl, 'attr') ?>"></div>
          <div class="tk-qr-ref"><?= esc($refCompact) ?></div>
        </div>
      </div>

      <?php if ($includes !== []): ?>
      <div class="tk-box">
        <div class="tk-box-label">Includes</div>
        <div class="tk-incl">
          <?php foreach (array_slice($includes, 0, 5) as $inc): ?>
            <div>
              <i class="bi <?= esc($inc['icon'] ?? 'bi-check-circle', 'attr') ?>"></i>
              <span class="t"><?= esc($inc['label'] ?? '') ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <div class="tk-box tk-pickup">
        <div>
          <div class="tk-box-label" style="margin-bottom:6px">Pick up point</div>
          <div class="place"><?= esc($ticket['pickup_point'] ?? 'To be confirmed') ?></div>
          <?php if (! empty($ticket['pickup_time'])): ?><div class="time">TIME: <?= esc($ticket['pickup_time']) ?></div><?php endif; ?>
        </div>
        <div class="tk-ty">
          <div class="s">Thank you!</div>
          <div class="n">See you on the adventure!</div>
        </div>
      </div>

      <div class="tk-perf"><span class="r"></span></div>
      <div class="tk-stub"><i class="bi bi-ticket-perforated"></i>Keep this ticket and show at check-in</div>
    </div>

    <div class="tk-actions">
      <button type="button" class="tk-btn tk-btn-primary" id="tk-download"><i class="bi bi-download"></i>Download PDF</button>
      <a class="tk-btn tk-btn-wa" target="_blank" rel="noopener"
         href="https://wa.me/?text=<?= rawurlencode('My MOROP GAA ticket ' . ($ticket['ticket_ref'] ?? '') . ': ' . $ticketUrl) ?>"><i class="bi bi-whatsapp"></i>Share on WhatsApp</a>
      <button type="button" class="tk-btn tk-btn-ghost" onclick="window.print()"><i class="bi bi-printer"></i>Print</button>
    </div>
    <p class="tk-note">Ticket <strong><?= esc($ticket['ticket_ref'] ?? '') ?></strong> · keep this link — it's your ticket.</p>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
  <script>
    // QR encodes the ticket URL so scanning at check-in opens/verifies it.
    (function(){
      var el = document.getElementById('qrcode');
      if (el && window.QRCode) {
        new QRCode(el, { text: el.getAttribute('data-url'), width: 208, height: 208, correctLevel: QRCode.CorrectLevel.M });
      }
    })();

    // Download the ticket as a PDF that matches what's on screen.
    document.getElementById('tk-download').addEventListener('click', function(){
      var btn = this, node = document.getElementById('ticket');
      btn.disabled = true; var old = btn.innerHTML; btn.innerHTML = '<i class="bi bi-hourglass-split"></i>Preparing…';
      html2canvas(node, { scale: 2, useCORS: true, backgroundColor: '#ffffff' }).then(function(canvas){
        var img = canvas.toDataURL('image/png');
        var jsPDF = window.jspdf.jsPDF;
        var pdf = new jsPDF({ orientation: 'portrait', unit: 'pt', format: [canvas.width/2, canvas.height/2] });
        pdf.addImage(img, 'PNG', 0, 0, canvas.width/2, canvas.height/2);
        pdf.save('<?= esc($ticket['ticket_ref'] ?? 'ticket', 'js') ?>.pdf');
        btn.disabled = false; btn.innerHTML = old;
      }).catch(function(){ btn.disabled = false; btn.innerHTML = old; alert('Could not generate the PDF — use Print → Save as PDF instead.'); });
    });
  </script>
</body>
</html>
