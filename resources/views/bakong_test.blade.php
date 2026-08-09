<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bakong Payment Test</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f4f5f7;
            margin: 0;
            padding: 40px 16px;
            color: #1a1a1a;
        }
        .card {
            max-width: 420px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        h1 { font-size: 20px; margin: 0 0 4px; }
        p.sub { color: #666; font-size: 13px; margin: 0 0 24px; }
        label { display: block; font-size: 13px; font-weight: 600; margin: 14px 0 6px; }
        input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }
        button {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            background: #0e2a47;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }
        button:disabled { opacity: 0.6; cursor: not-allowed; }
        .qr-box {
            text-align: center;
            margin-top: 24px;
            display: none;
        }
        .qr-box img {
            width: 220px;
            height: 220px;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 10px;
        }
        .status {
            margin-top: 14px;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
        }
        .status.pending { background: #fff6e0; color: #9a6700; }
        .status.paid { background: #e3f9e5; color: #1a7f37; }
        .status.failed { background: #ffe8e6; color: #cf222e; }
        .error-box {
            margin-top: 16px;
            padding: 12px 14px;
            background: #ffe8e6;
            color: #cf222e;
            border-radius: 8px;
            font-size: 13px;
            display: none;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>

<div class="card">
    <h1>Pay with Bakong KHQR</h1>
    <p class="sub">Test page — generates a real sandbox QR via your Bakong integration.</p>

    <form id="payForm">
        <label>Order ID</label>
        <input type="text" name="order_id" value="1" required>

        <label>Amount</label>
        <input type="number" step="0.01" name="amount" value="0.01" required>

        <label>Currency</label>
        <input type="text" name="currency" value="USD" required>

        <label>Account Name (payee display name)</label>
        <input type="text" name="account_name" value="Tong Seng" required>

        <label>Customer Name</label>
        <input type="text" name="customer_name" value="Test Customer">

        <label>Customer Email</label>
        <input type="email" name="customer_email" value="test@example.com">

        <label>Customer Phone</label>
        <input type="text" name="customer_phone" value="012345678">

        <button type="submit" id="submitBtn">Generate QR & Pay</button>
    </form>

    <div class="error-box" id="errorBox"></div>

    <div class="qr-box" id="qrBox">
        <img id="qrImage" src="" alt="Bakong QR Code">
        <div class="status pending" id="statusBadge">Waiting for payment…</div>
    </div>
</div>

<script>
const form = document.getElementById('payForm');
const submitBtn = document.getElementById('submitBtn');
const qrBox = document.getElementById('qrBox');
const qrImage = document.getElementById('qrImage');
const statusBadge = document.getElementById('statusBadge');
const errorBox = document.getElementById('errorBox');
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

let pollTimer = null;

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    errorBox.style.display = 'none';
    qrBox.style.display = 'none';
    clearInterval(pollTimer);

    submitBtn.disabled = true;
    submitBtn.textContent = 'Generating…';

    const formData = new FormData(form);
    const payload = Object.fromEntries(formData.entries());

    try {
        const res = await fetch("{{ route('bakong.test.pay') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(payload),
        });

        const data = await res.json();

        if (!res.ok || data.error) {
            throw new Error(data.message || JSON.stringify(data.bakong) || 'Failed to generate QR.');
        }

        if (!data.qr_url) {
            throw new Error('No QR was returned. Check your BAKONG_TOKEN / account config.');
        }

        qrImage.src = data.qr_url;
        qrBox.style.display = 'block';
        statusBadge.className = 'status pending';
        statusBadge.textContent = 'Waiting for payment…';

        const paymentId = data.payment.id;
        startPolling(paymentId);

    } catch (err) {
        errorBox.textContent = err.message;
        errorBox.style.display = 'block';
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Generate QR & Pay';
    }
});

function startPolling(paymentId) {
    pollTimer = setInterval(async () => {
        try {
            const res = await fetch(`/bakong-test/status/${paymentId}`, {
                headers: { 'Accept': 'application/json' },
            });
            const data = await res.json();

            if (data.status === 'paid') {
                clearInterval(pollTimer);

                // Remove the "waiting" badge entirely and swap in a success message
                statusBadge.className = 'status paid';
                statusBadge.textContent = '✅ Payment Successful!';

                // Optionally hide the QR now that it's no longer needed
                qrImage.style.opacity = '0.3';

                alert('✅ Payment successful! Your Bakong payment has been received.');

            } else if (data.status === 'failed') {
                clearInterval(pollTimer);

                statusBadge.className = 'status failed';
                statusBadge.textContent = '❌ Payment failed.';

                alert('❌ Payment failed. Please try again.');

            } else {
                statusBadge.className = 'status pending';
                statusBadge.textContent = 'Waiting for payment…';
            }
        } catch (err) {
            console.error('Polling error', err);
        }
    }, 4000);
}
</script>

</body>
</html>