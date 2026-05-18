<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title>WHOIS Lookup - Domain Registration Information | JowHost Tools</title>
    <meta name="title" content="WHOIS Lookup - Domain Registration Information | JowHost Tools">
    <meta name="description" content="Free WHOIS lookup tool to check domain registration details, owner information, registrar, nameservers, creation date, expiration date, and DNS records. Fast & accurate real-time results.">
    <meta name="keywords" content="whois lookup, domain whois, whois checker, domain registration, domain owner, domain info, whois search, domain registrar, domain expiry, domain availability">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Indonesian">
    <meta name="author" content="JowHost">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://tools.jowhost.com/whois/">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://tools.jowhost.com/whois/">
    <meta property="og:title" content="WHOIS Lookup - Domain Registration Information">
    <meta property="og:description" content="Free WHOIS lookup tool. Check domain registration details, owner information, registrar, nameservers, and expiration dates.">
    <meta property="og:image" content="https://tools.jowhost.com/og-image.jpg">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://tools.jowhost.com/whois/">
    <meta name="twitter:title" content="WHOIS Lookup - Domain Information Checker">
    <meta name="twitter:description" content="Free WHOIS lookup tool with real-time results. Check domain registration and ownership details.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2563eb">
    
    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebApplication",
        "name": "WHOIS Lookup Tool",
        "applicationCategory": "NetworkApplication",
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "USD"
        },
        "description": "Free WHOIS lookup tool to check domain registration details, owner information, registrar, nameservers, and expiration dates.",
        "url": "https://tools.jowhost.com/whois/",
        "author": {
            "@type": "Organization",
            "name": "JowHost"
        }
    }
    </script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --color-primary: #2563eb;
            --color-primary-dark: #1e40af;
            --color-accent: #0ea5e9;
            --color-success: #10b981;
            --color-warning: #f59e0b;
            --color-error: #ef4444;
            --color-text: #1f2937;
            --color-text-light: #6b7280;
            --color-bg: #f9fafb;
            --color-white: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: var(--color-text);
            line-height: 1.6;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
            color: white;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.95;
        }

        .card {
            background: var(--color-white);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: var(--shadow-lg);
            margin-bottom: 2rem;
        }

        .search-box {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .search-box input {
            flex: 1;
            padding: 0.875rem 1.25rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn {
            padding: 0.875rem 2rem;
            background: var(--color-primary);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .btn:hover {
            background: var(--color-primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }

        .examples {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .example-badge {
            padding: 0.25rem 0.75rem;
            background: #f3f4f6;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            color: var(--color-text-light);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .example-badge:hover {
            background: #e5e7eb;
            color: var(--color-text);
        }

        .result-box {
            display: none;
            margin-top: 2rem;
        }

        .result-box.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .result-box.loading {
            text-align: center;
            color: var(--color-text-light);
        }

        .spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid var(--color-primary);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .result-box h3 {
            font-size: 1.25rem;
            color: var(--color-text);
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f3f4f6;
        }

        .whois-data {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1.5rem;
            overflow-x: auto;
        }

        .whois-data pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            font-family: 'Monaco', 'Menlo', 'Courier New', monospace;
            font-size: 0.875rem;
            line-height: 1.6;
            color: #374151;
            margin: 0;
        }

        .error {
            padding: 1rem;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 0.5rem;
            color: var(--color-error);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-section {
            margin-top: 2rem;
        }

        .info-section h2 {
            font-size: 1.5rem;
            color: var(--color-text);
            margin-bottom: 1rem;
        }

        .info-section p {
            color: var(--color-text-light);
            margin-bottom: 0.75rem;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .feature-item {
            padding: 1rem;
            background: #f9fafb;
            border-radius: 0.5rem;
            border-left: 3px solid var(--color-primary);
        }

        .feature-item strong {
            display: block;
            color: var(--color-text);
            margin-bottom: 0.25rem;
        }

        .feature-item span {
            color: var(--color-text-light);
            font-size: 0.875rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            text-decoration: none;
            margin-bottom: 2rem;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .back-link:hover {
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }

            .search-box {
                flex-direction: column;
            }

            .card {
                padding: 1.5rem;
            }

            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="/" class="back-link">← Kembali ke Semua Tools</a>
        
        <div class="header">
            <h1>🔍 WHOIS Lookup</h1>
            <p>Cek informasi registrasi domain secara lengkap</p>
        </div>

        <div class="card">
            <div class="search-box">
                <input 
                    type="text" 
                    id="domain-input" 
                    placeholder="Masukkan nama domain (contoh: example.com)" 
                    autocomplete="off"
                >
                <button class="btn" onclick="checkWhois()">Cek WHOIS</button>
            </div>

            <div class="examples">
                <span style="color: var(--color-text-light); font-size: 0.875rem;">Contoh:</span>
                <span class="example-badge" onclick="setExample('google.com')">google.com</span>
                <span class="example-badge" onclick="setExample('github.com')">github.com</span>
                <span class="example-badge" onclick="setExample('cloudflare.com')">cloudflare.com</span>
            </div>

            <div id="result-box" class="result-box"></div>
        </div>

        <div class="card info-section">
            <h2>Apa itu WHOIS?</h2>
            <p>
                WHOIS adalah protokol query dan response yang digunakan untuk mencari informasi tentang 
                registrasi nama domain dan alamat IP. Tool ini membantu Anda menemukan detail penting tentang 
                kepemilikan dan konfigurasi domain.
            </p>

            <div class="features">
                <div class="feature-item">
                    <strong>📋 Informasi Registrar</strong>
                    <span>Detail perusahaan yang mendaftarkan domain</span>
                </div>
                <div class="feature-item">
                    <strong>👤 Data Pemilik</strong>
                    <span>Informasi kontak dan organisasi pemilik domain</span>
                </div>
                <div class="feature-item">
                    <strong>📅 Tanggal Penting</strong>
                    <span>Tanggal pendaftaran, update, dan kadaluarsa</span>
                </div>
                <div class="feature-item">
                    <strong>🌐 Nameservers</strong>
                    <span>Daftar nameserver yang digunakan domain</span>
                </div>
                <div class="feature-item">
                    <strong>🔒 Status Domain</strong>
                    <span>Status keamanan dan transfer domain</span>
                </div>
                <div class="feature-item">
                    <strong>⚡ Real-time</strong>
                    <span>Data langsung dari WHOIS server resmi</span>
                </div>
            </div>
        </div>

        <div class="card">
            <h2>Kegunaan WHOIS Lookup</h2>
            <p><strong>✓ Verifikasi Kepemilikan Domain</strong> - Pastikan domain dimiliki oleh entitas yang benar</p>
            <p><strong>✓ Cek Tanggal Kadaluarsa</strong> - Ketahui kapan domain akan expire untuk renewal</p>
            <p><strong>✓ Kontak Pemilik Domain</strong> - Temukan informasi kontak untuk keperluan bisnis</p>
            <p><strong>✓ Investigasi Keamanan</strong> - Analisis domain untuk keperluan cybersecurity</p>
            <p><strong>✓ Riset Kompetitor</strong> - Pelajari informasi domain kompetitor</p>
            <p><strong>✓ Pembelian Domain</strong> - Cek ketersediaan dan informasi domain yang ingin dibeli</p>
        </div>
    </div>

    <script>
        function setExample(domain) {
            document.getElementById('domain-input').value = domain;
            checkWhois();
        }

        async function checkWhois() {
            const domain = document.getElementById('domain-input').value.trim();
            const resultBox = document.getElementById('result-box');

            if (!domain) {
                alert('Silakan masukkan nama domain!');
                return;
            }

            // Show loading state
            resultBox.className = 'result-box show loading';
            resultBox.innerHTML = '<div class="spinner"></div><span>Mengambil data WHOIS...</span>';

            try {
                const formData = new URLSearchParams();
                formData.append('action', 'whois');
                formData.append('query', domain);

                const response = await fetch('/api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: formData.toString()
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                resultBox.className = 'result-box show';
                
                if (data.success) {
                    resultBox.innerHTML = `
                        <h3>${data.title || 'WHOIS Information'}</h3>
                        <div class="whois-data">
                            ${data.data[0].value}
                        </div>
                    `;
                } else {
                    resultBox.innerHTML = `
                        <div class="error">
                            ❌ ${data.message || 'Gagal mendapatkan data WHOIS'}
                        </div>
                    `;
                }
            } catch (error) {
                resultBox.className = 'result-box show';
                resultBox.innerHTML = `
                    <div class="error">
                        ❌ Error: ${error.message || 'Koneksi gagal'}
                    </div>
                `;
            }
        }

        // Enter key support
        document.getElementById('domain-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                checkWhois();
            }
        });

        // Auto-focus on input
        document.getElementById('domain-input').focus();
    </script>
</body>
</html>
