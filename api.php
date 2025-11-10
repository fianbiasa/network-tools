<?php
header('Content-Type: application/json');
error_reporting(0);

$action = $_POST['action'] ?? '';
$query = trim($_POST['query'] ?? '');

function respond($success, $data = [], $message = '', $title = '') {
    echo json_encode([
        'success' => $success,
        'data' => $data,
        'message' => $message,
        'title' => $title
    ]);
    exit;
}

function cleanDomain($domain) {
    $domain = strtolower(trim($domain));
    $domain = preg_replace('#^https?://#', '', $domain);
    $domain = preg_replace('#[:/].*$#', '', $domain);
    return $domain;
}

if (empty($query)) {
    respond(false, [], 'Please provide a domain or IP address');
}

switch ($action) {
    // DNS A Record
    case 'a':
        $domain = cleanDomain($query);
        $records = @dns_get_record($domain, DNS_A);
        if (!$records) respond(false, [], 'No A records found');
        
        $result = [];
        foreach ($records as $i => $r) {
            $result[] = ['label' => "A Record " . ($i+1), 'value' => $r['ip'] . " (TTL: {$r['ttl']})"];
        }
        respond(true, $result, '', "A Records for {$domain}");
        break;

    // DNS AAAA Record
    case 'aaaa':
        $domain = cleanDomain($query);
        $records = @dns_get_record($domain, DNS_AAAA);
        if (!$records) respond(false, [], 'No AAAA (IPv6) records found');
        
        $result = [];
        foreach ($records as $i => $r) {
            $result[] = ['label' => "AAAA Record " . ($i+1), 'value' => $r['ipv6'] . " (TTL: {$r['ttl']})"];
        }
        respond(true, $result, '', "AAAA (IPv6) Records for {$domain}");
        break;

    // MX Record
    case 'mx':
        $domain = cleanDomain($query);
        $records = @dns_get_record($domain, DNS_MX);
        if (!$records) respond(false, [], 'No MX records found');
        
        $result = [];
        foreach ($records as $i => $r) {
            $ip = @gethostbyname($r['target']);
            $result[] = [
                'label' => "MX Server " . ($i+1),
                'value' => "{$r['target']} (Priority: {$r['pri']}, IP: {$ip})"
            ];
        }
        respond(true, $result, '', "MX Records for {$domain}");
        break;

    // NS Record
    case 'ns':
        $domain = cleanDomain($query);
        $records = @dns_get_record($domain, DNS_NS);
        if (!$records) respond(false, [], 'No nameserver records found');
        
        $result = [];
        foreach ($records as $i => $r) {
            $ip = @gethostbyname($r['target']);
            $result[] = [
                'label' => "Nameserver " . ($i+1),
                'value' => "{$r['target']} ({$ip})"
            ];
        }
        respond(true, $result, '', "Nameservers for {$domain}");
        break;

    // CNAME Record
    case 'cname':
        $domain = cleanDomain($query);
        $result = [];
        
        // Check if input is already a subdomain or just domain
        $hasSubdomain = substr_count($domain, '.') > 1;
        
        if ($hasSubdomain) {
            // User provided subdomain, check it directly
            $records = @dns_get_record($domain, DNS_CNAME);
            if ($records && count($records) > 0) {
                foreach ($records as $i => $r) {
                    $result[] = [
                        'label' => "CNAME for {$domain}",
                        'value' => "{$r['target']} (TTL: {$r['ttl']})"
                    ];
                }
                respond(true, $result, '', "CNAME Records for {$domain}");
            } else {
                // Check if it has A record instead
                $aRecords = @dns_get_record($domain, DNS_A);
                if ($aRecords && count($aRecords) > 0) {
                    respond(false, [], "{$domain} memiliki A record ({$aRecords[0]['ip']}), bukan CNAME. Domain/subdomain ini mengarah langsung ke IP.");
                } else {
                    respond(false, [], "Tidak ada CNAME record untuk {$domain}. Pastikan subdomain benar atau coba subdomain lain seperti www, mail, dll.");
                }
            }
        } else {
            // User provided domain only, check common subdomains
            $commonSubdomains = ['www', 'mail', 'ftp', 'blog', 'shop', 'api', 'cdn', 'cpanel'];
            
            foreach ($commonSubdomains as $sub) {
                $fullDomain = "{$sub}.{$domain}";
                $records = @dns_get_record($fullDomain, DNS_CNAME);
                
                if ($records && count($records) > 0) {
                    foreach ($records as $r) {
                        $result[] = [
                            'label' => "CNAME {$fullDomain}",
                            'value' => "{$r['target']} (TTL: {$r['ttl']})"
                        ];
                    }
                }
            }
            
            if (count($result) > 0) {
                respond(true, $result, '', "CNAME Records ditemukan");
            } else {
                // Check if main domain has A record
                $aRecords = @dns_get_record($domain, DNS_A);
                if ($aRecords && count($aRecords) > 0) {
                    $result[] = [
                        'label' => "❌ Tidak ada CNAME",
                        'value' => "{$domain} menggunakan A record ({$aRecords[0]['ip']})"
                    ];
                    $result[] = [
                        'label' => "💡 Tips",
                        'value' => "CNAME biasanya ada di subdomain. Coba ketik: www.{$domain}"
                    ];
                    respond(true, $result, '', "Informasi DNS {$domain}");
                } else {
                    respond(false, [], "Tidak ditemukan CNAME atau A record untuk {$domain}. Periksa kembali nama domain.");
                }
            }
        }
        break;

    // TXT Record
    case 'txt':
        $domain = cleanDomain($query);
        $records = @dns_get_record($domain, DNS_TXT);
        if (!$records) respond(false, [], 'No TXT records found');
        
        $result = [];
        foreach ($records as $i => $r) {
            $result[] = [
                'label' => "TXT Record " . ($i+1),
                'value' => $r['txt']
            ];
        }
        respond(true, $result, '', "TXT Records for {$domain}");
        break;

    // SOA Record
    case 'soa':
        $domain = cleanDomain($query);
        $records = @dns_get_record($domain, DNS_SOA);
        if (!$records) respond(false, [], 'No SOA records found');
        
        $r = $records[0];
        $result = [
            ['label' => 'Primary Nameserver', 'value' => $r['mname']],
            ['label' => 'Responsible Person', 'value' => $r['rname']],
            ['label' => 'Serial Number', 'value' => $r['serial']],
            ['label' => 'Refresh', 'value' => $r['refresh'] . ' seconds'],
            ['label' => 'Retry', 'value' => $r['retry'] . ' seconds'],
            ['label' => 'Expire', 'value' => $r['expire'] . ' seconds'],
            ['label' => 'Minimum TTL', 'value' => $r['minimum-ttl'] . ' seconds'],
        ];
        respond(true, $result, '', "SOA Record for {$domain}");
        break;

    // SRV Record
    case 'srv':
        $domain = cleanDomain($query);
        $records = @dns_get_record($domain, DNS_SRV);
        if (!$records) respond(false, [], 'No SRV records found');
        
        $result = [];
        foreach ($records as $i => $r) {
            $result[] = [
                'label' => "SRV Record " . ($i+1),
                'value' => "{$r['target']}:{$r['port']} (Priority: {$r['pri']}, Weight: {$r['weight']})"
            ];
        }
        respond(true, $result, '', "SRV Records for {$domain}");
        break;

    // PTR Record
    case 'ptr':
        $ip = trim($query);
        if (!filter_var($ip, FILTER_VALIDATE_IP)) respond(false, [], 'Invalid IP address');
        
        $hostname = @gethostbyaddr($ip);
        if ($hostname === $ip) respond(false, [], 'No PTR record found');
        
        $forward = @gethostbyname($hostname);
        $result = [
            ['label' => 'IP Address', 'value' => $ip],
            ['label' => 'PTR Record', 'value' => $hostname],
            ['label' => 'Forward Lookup', 'value' => $forward],
            ['label' => 'Match Status', 'value' => ($forward === $ip) ? '✅ Match' : '⚠️ Mismatch'],
        ];
        respond(true, $result, '', "PTR Record for {$ip}");
        break;

    // All DNS Records
    case 'all':
        $domain = cleanDomain($query);
        $types = [DNS_A, DNS_AAAA, DNS_MX, DNS_NS, DNS_TXT, DNS_CNAME, DNS_SOA];
        $result = [];
        
        foreach ($types as $type) {
            $records = @dns_get_record($domain, $type);
            if ($records) {
                foreach ($records as $r) {
                    $label = $r['type'];
                    $value = '';
                    switch ($r['type']) {
                        case 'A': $value = $r['ip']; break;
                        case 'AAAA': $value = $r['ipv6']; break;
                        case 'MX': $value = "{$r['target']} (Priority: {$r['pri']})"; break;
                        case 'NS': $value = $r['target']; break;
                        case 'TXT': $value = $r['txt']; break;
                        case 'CNAME': $value = $r['target']; break;
                        case 'SOA': $value = "{$r['mname']} (Serial: {$r['serial']})"; break;
                    }
                    $result[] = ['label' => $label, 'value' => $value];
                }
            }
        }
        if (empty($result)) respond(false, [], 'No DNS records found');
        respond(true, $result, '', "All DNS Records for {$domain}");
        break;

    // SPF Record
    case 'spf':
        $domain = cleanDomain($query);
        $records = @dns_get_record($domain, DNS_TXT);
        if (!$records) respond(false, [], 'No TXT/SPF records found');
        
        $result = [];
        $spfFound = false;
        foreach ($records as $r) {
            if (stripos($r['txt'], 'v=spf1') !== false) {
                $spfFound = true;
                $result[] = ['label' => 'SPF Record', 'value' => $r['txt']];
                
                // Parse SPF
                if (strpos($r['txt'], 'all') !== false) {
                    $policy = preg_match('/([-~+?]all)/', $r['txt'], $m) ? $m[1] : '';
                    $policies = [
                        '-all' => '✅ Strict (Reject)',
                        '~all' => '⚠️ Soft Fail',
                        '+all' => '❌ Pass All (Not Recommended)',
                        '?all' => '⚠️ Neutral'
                    ];
                    $result[] = ['label' => 'Policy', 'value' => $policies[$policy] ?? $policy];
                }
            }
        }
        if (!$spfFound) respond(false, [], 'No SPF record found');
        respond(true, $result, '', "SPF Record for {$domain}");
        break;

    // DKIM Record
    case 'dkim':
        if (!strpos($query, ':')) respond(false, [], 'Format: selector:domain (e.g., default:example.com)');
        list($selector, $domain) = explode(':', $query, 2);
        $selector = trim($selector);
        $domain = cleanDomain($domain);
        $dkimDomain = "{$selector}._domainkey.{$domain}";
        
        // Try multiple methods to get DKIM record
        $records = @dns_get_record($dkimDomain, DNS_TXT);
        
        // If dns_get_record fails, try using dig command
        if (!$records) {
            exec("dig +short TXT " . escapeshellarg($dkimDomain) . " 2>&1", $digOutput);
            if (!empty($digOutput)) {
                $records = [];
                foreach ($digOutput as $line) {
                    $line = trim($line, '"');
                    $line = str_replace('" "', '', $line); // Handle split TXT records
                    if (!empty($line)) {
                        $records[] = ['txt' => $line, 'type' => 'TXT'];
                    }
                }
            }
        }
        
        if (!$records) {
            // Try nslookup as fallback
            exec("nslookup -type=TXT " . escapeshellarg($dkimDomain) . " 2>&1", $nsOutput);
            $txtData = '';
            $inTxt = false;
            foreach ($nsOutput as $line) {
                if (strpos($line, 'text = ') !== false) {
                    $inTxt = true;
                    $txtData .= trim(str_replace('text = ', '', $line), '"');
                } elseif ($inTxt && strpos($line, '"') !== false) {
                    $txtData .= trim($line, '" ');
                } elseif ($inTxt && empty(trim($line))) {
                    break;
                }
            }
            if (!empty($txtData)) {
                $records[] = ['txt' => $txtData, 'type' => 'TXT'];
            }
        }
        
        if (!$records) respond(false, [], "No DKIM record found for selector '{$selector}' at {$dkimDomain}");
        
        $result = [];
        $dkimFound = false;
        
        foreach ($records as $r) {
            $txt = $r['txt'];
            // DKIM record might not always have v=DKIM1, check for common DKIM attributes
            if (stripos($txt, 'DKIM1') !== false || 
                (strpos($txt, 'k=') !== false && strpos($txt, 'p=') !== false)) {
                $dkimFound = true;
                
                // Truncate very long public keys for display
                $displayTxt = strlen($txt) > 500 ? substr($txt, 0, 500) . '...' : $txt;
                $result[] = ['label' => 'DKIM Record', 'value' => $displayTxt];
                $result[] = ['label' => 'Selector', 'value' => $selector];
                $result[] = ['label' => 'Domain', 'value' => $domain];
                $result[] = ['label' => 'Query', 'value' => $dkimDomain];
                
                // Parse DKIM attributes
                if (preg_match('/v=([^;]+)/', $txt, $m)) {
                    $result[] = ['label' => 'Version', 'value' => $m[1]];
                }
                if (preg_match('/k=([^;]+)/', $txt, $m)) {
                    $result[] = ['label' => 'Key Type', 'value' => $m[1]];
                }
                if (preg_match('/p=([^;]+)/', $txt, $m)) {
                    $keyLen = strlen($m[1]);
                    $result[] = ['label' => 'Public Key', 'value' => "Present ({$keyLen} chars)"];
                }
                if (preg_match('/t=([^;]+)/', $txt, $m)) {
                    $flags = explode(':', $m[1]);
                    $result[] = ['label' => 'Flags', 'value' => implode(', ', $flags)];
                }
                
                $result[] = ['label' => 'Status', 'value' => '✅ DKIM Configured'];
            }
        }
        
        if (!$dkimFound) {
            // Show what we found anyway
            $result[] = ['label' => 'Query', 'value' => $dkimDomain];
            $result[] = ['label' => 'DNS Response', 'value' => $records[0]['txt']];
            $result[] = ['label' => 'Status', 'value' => '⚠️ Found TXT record but not valid DKIM format'];
        }
        
        respond(true, $result, '', "DKIM Record for {$domain}");
        break;

    // DMARC Record
    case 'dmarc':
        $domain = cleanDomain($query);
        $dmarcDomain = "_dmarc.{$domain}";
        $records = @dns_get_record($dmarcDomain, DNS_TXT);
        if (!$records) respond(false, [], 'No DMARC record found');
        
        $result = [];
        foreach ($records as $r) {
            if (stripos($r['txt'], 'v=DMARC1') !== false) {
                $result[] = ['label' => 'DMARC Record', 'value' => $r['txt']];
                
                // Parse DMARC
                preg_match('/p=([^;]+)/', $r['txt'], $m);
                $policy = $m[1] ?? 'none';
                $policies = [
                    'none' => '⚠️ Monitor Only',
                    'quarantine' => '📬 Quarantine',
                    'reject' => '✅ Reject'
                ];
                $result[] = ['label' => 'Policy', 'value' => $policies[$policy] ?? $policy];
                
                if (preg_match('/rua=([^;]+)/', $r['txt'], $m)) {
                    $result[] = ['label' => 'Report Email', 'value' => str_replace('mailto:', '', $m[1])];
                }
            }
        }
        if (empty($result)) respond(false, [], 'No valid DMARC record found');
        respond(true, $result, '', "DMARC Record for {$domain}");
        break;

    // Blacklist Check
    case 'blacklist':
        $input = trim($query);
        // Convert domain to IP if needed
        if (!filter_var($input, FILTER_VALIDATE_IP)) {
            $input = @gethostbyname(cleanDomain($input));
            if (!filter_var($input, FILTER_VALIDATE_IP)) {
                respond(false, [], 'Could not resolve to IP address');
            }
        }
        
        $dnsbl = [
            'zen.spamhaus.org' => 'Spamhaus ZEN',
            'bl.spamcop.net' => 'SpamCop',
            'b.barracudacentral.org' => 'Barracuda',
            'dnsbl.sorbs.net' => 'SORBS',
            'cbl.abuseat.org' => 'CBL',
            'psbl.surriel.com' => 'PSBL'
        ];
        
        $reverse = implode('.', array_reverse(explode('.', $input)));
        $result = [];
        $listed = 0;
        
        foreach ($dnsbl as $bl => $name) {
            $lookup = "{$reverse}.{$bl}";
            $check = @gethostbyname($lookup);
            $isListed = ($check !== $lookup && strpos($check, '127.0.0') === 0);
            
            $result[] = [
                'label' => $name,
                'value' => $isListed ? '❌ Listed' : '✅ Clean'
            ];
            if ($isListed) $listed++;
        }
        
        array_unshift($result, [
            'label' => 'IP Address',
            'value' => $input
        ], [
            'label' => 'Overall Status',
            'value' => $listed === 0 ? '✅ Not Blacklisted' : "⚠️ Listed on {$listed} blacklist(s)"
        ]);
        
        respond(true, $result, '', "Blacklist Check for {$input}");
        break;

    // SSL Certificate
    case 'ssl':
    case 'ssl-expiry':
        $domain = cleanDomain($query);
        $context = stream_context_create([
            "ssl" => [
                "capture_peer_cert" => true,
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ]);

        $stream = @stream_socket_client(
            "ssl://{$domain}:443",
            $errno,
            $errstr,
            30,
            STREAM_CLIENT_CONNECT,
            $context
        );

        if (!$stream) respond(false, [], "Unable to connect to {$domain}:443");

        $params = stream_context_get_params($stream);
        $cert = openssl_x509_parse($params['options']['ssl']['peer_certificate']);
        if (!$cert) respond(false, [], 'Unable to parse SSL certificate');

        $validFrom = date('Y-m-d H:i:s', $cert['validFrom_time_t']);
        $validTo = date('Y-m-d H:i:s', $cert['validTo_time_t']);
        $daysLeft = floor(($cert['validTo_time_t'] - time()) / 86400);
        $isValid = $daysLeft > 0;

        $result = [
            ['label' => 'Status', 'value' => $isValid ? '✅ Valid' : '❌ Expired'],
            ['label' => 'Common Name', 'value' => $cert['subject']['CN'] ?? 'N/A'],
            ['label' => 'Issued By', 'value' => $cert['issuer']['O'] ?? 'N/A'],
            ['label' => 'Valid From', 'value' => $validFrom],
            ['label' => 'Valid Until', 'value' => $validTo],
            ['label' => 'Days Remaining', 'value' => $daysLeft . ' days' . ($daysLeft < 30 ? ' ⚠️' : '')],
        ];

        if ($action === 'ssl') {
            $result[] = ['label' => 'Serial Number', 'value' => $cert['serialNumber'] ?? 'N/A'];
            $result[] = ['label' => 'Signature Algorithm', 'value' => $cert['signatureTypeSN'] ?? 'N/A'];
            if (isset($cert['extensions']['subjectAltName'])) {
                $sans = str_replace('DNS:', '', $cert['extensions']['subjectAltName']);
                $result[] = ['label' => 'Subject Alt Names', 'value' => $sans];
            }
        }

        respond(true, $result, '', "SSL Certificate for {$domain}");
        break;

    // WHOIS Lookup
    case 'whois':
        $domain = cleanDomain($query);
        
        // Function to perform WHOIS lookup via socket
        function whoisLookup($domain) {
            // Determine WHOIS server based on TLD
            $tld = substr(strrchr($domain, "."), 1);
            $whoisServers = [
                'com' => 'whois.verisign-grs.com',
                'net' => 'whois.verisign-grs.com',
                'org' => 'whois.pir.org',
                'info' => 'whois.afilias.net',
                'biz' => 'whois.biz',
                'us' => 'whois.nic.us',
                'uk' => 'whois.nic.uk',
                'co.uk' => 'whois.nic.uk',
                'io' => 'whois.nic.io',
                'me' => 'whois.nic.me',
                'xyz' => 'whois.nic.xyz',
                'online' => 'whois.nic.online',
                'site' => 'whois.nic.site',
                'tech' => 'whois.nic.tech',
                'store' => 'whois.nic.store',
                'dev' => 'whois.nic.google',
                'app' => 'whois.nic.google',
                'id' => 'whois.id',
                'co.id' => 'whois.id',
            ];
            
            $whoisServer = $whoisServers[$tld] ?? 'whois.iana.org';
            
            // Perform WHOIS query
            $fp = @fsockopen($whoisServer, 43, $errno, $errstr, 10);
            if (!$fp) {
                return false;
            }
            
            fputs($fp, $domain . "\r\n");
            $response = '';
            while (!feof($fp)) {
                $response .= fgets($fp, 128);
            }
            fclose($fp);
            
            // If IANA returns a referral, follow it
            if ($whoisServer === 'whois.iana.org' && preg_match('/whois:\s+([^\s]+)/i', $response, $matches)) {
                $referralServer = $matches[1];
                $fp = @fsockopen($referralServer, 43, $errno, $errstr, 10);
                if ($fp) {
                    fputs($fp, $domain . "\r\n");
                    $response = '';
                    while (!feof($fp)) {
                        $response .= fgets($fp, 128);
                    }
                    fclose($fp);
                }
            }
            
            return $response;
        }
        
        $whoisData = whoisLookup($domain);
        
        if (!$whoisData) {
            respond(false, [], 'WHOIS lookup failed. Unable to connect to WHOIS server.');
        }
        
        // Parse important information
        $result = [
            ['label' => 'WHOIS Data', 'value' => "<pre style='white-space: pre-wrap; font-size: 0.85em;'>" . htmlspecialchars($whoisData) . "</pre>"]
        ];
        
        respond(true, $result, '', "WHOIS Information for {$domain}");
        break;

    // Domain Health Check
    case 'domain-health':
        $domain = cleanDomain($query);
        $result = [];
        $issues = 0;
        
        // Check A record
        $a = @dns_get_record($domain, DNS_A);
        $result[] = ['label' => 'A Record', 'value' => $a ? '✅ Configured' : '❌ Missing'];
        if (!$a) $issues++;
        
        // Check MX record
        $mx = @dns_get_record($domain, DNS_MX);
        $result[] = ['label' => 'MX Record', 'value' => $mx ? '✅ Configured' : '⚠️ Missing'];
        if (!$mx) $issues++;
        
        // Check NS record
        $ns = @dns_get_record($domain, DNS_NS);
        $result[] = ['label' => 'Nameservers', 'value' => $ns ? '✅ Configured (' . count($ns) . ')' : '❌ Missing'];
        if (!$ns) $issues++;
        
        // Check SSL
        $ssl = @fsockopen("ssl://{$domain}", 443, $errno, $errstr, 5);
        $result[] = ['label' => 'SSL Certificate', 'value' => $ssl ? '✅ Installed' : '⚠️ Not Found'];
        if ($ssl) fclose($ssl);
        
        // Check SPF
        $txt = @dns_get_record($domain, DNS_TXT);
        $spf = false;
        if ($txt) {
            foreach ($txt as $r) {
                if (stripos($r['txt'], 'v=spf1') !== false) $spf = true;
            }
        }
        $result[] = ['label' => 'SPF Record', 'value' => $spf ? '✅ Configured' : '⚠️ Missing'];
        
        // Check DMARC
        $dmarc = @dns_get_record("_dmarc.{$domain}", DNS_TXT);
        $result[] = ['label' => 'DMARC Record', 'value' => $dmarc ? '✅ Configured' : '⚠️ Missing'];
        
        array_unshift($result, [
            'label' => 'Overall Health',
            'value' => $issues === 0 ? '✅ Excellent' : ($issues < 3 ? '⚠️ Good' : '❌ Needs Attention')
        ]);
        
        respond(true, $result, '', "Domain Health Check for {$domain}");
        break;

    // Ping Test
    case 'ping':
        $host = cleanDomain($query);
        if (!filter_var($host, FILTER_VALIDATE_IP)) {
            $host = @gethostbyname($host);
        }
        
        exec("ping -c 4 " . escapeshellarg($host) . " 2>&1", $output, $return);
        
        $result = [
            ['label' => 'Target', 'value' => $host],
            ['label' => 'Status', 'value' => $return === 0 ? '✅ Reachable' : '❌ Unreachable']
        ];
        
        foreach ($output as $line) {
            if (preg_match('/time=(.+)/', $line, $m)) {
                $result[] = ['label' => 'Response Time', 'value' => $m[1]];
            }
            if (preg_match('/min\/avg\/max/', $line)) {
                $result[] = ['label' => 'Statistics', 'value' => $line];
            }
        }
        
        respond(true, $result, '', "Ping Test for {$query}");
        break;

    // Port Check
    case 'port':
        if (!strpos($query, ':')) respond(false, [], 'Format: domain:port (e.g., example.com:443)');
        list($host, $port) = explode(':', $query, 2);
        $host = cleanDomain($host);
        $port = (int)$port;
        
        if ($port < 1 || $port > 65535) respond(false, [], 'Invalid port number');
        
        $fp = @fsockopen($host, $port, $errno, $errstr, 5);
        $result = [
            ['label' => 'Host', 'value' => $host],
            ['label' => 'Port', 'value' => $port],
            ['label' => 'Status', 'value' => $fp ? '✅ Open' : '❌ Closed or Filtered'],
        ];
        
        if ($fp) {
            $result[] = ['label' => 'Connection', 'value' => 'Successful'];
            fclose($fp);
        } else {
            $result[] = ['label' => 'Error', 'value' => $errstr];
        }
        
        respond(true, $result, '', "Port Check for {$host}:{$port}");
        break;

    // IP Information
    case 'ip-info':
        $ip = trim($query);
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $ip = @gethostbyname(cleanDomain($ip));
        }
        
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            respond(false, [], 'Invalid IP address');
        }
        
        $hostname = @gethostbyaddr($ip);
        $result = [
            ['label' => 'IP Address', 'value' => $ip],
            ['label' => 'IP Version', 'value' => filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? 'IPv6' : 'IPv4'],
            ['label' => 'Hostname', 'value' => $hostname !== $ip ? $hostname : 'No PTR record'],
        ];
        
        // Try to get geolocation from ip-api.com
        $geo = @file_get_contents("http://ip-api.com/json/{$ip}");
        if ($geo) {
            $data = json_decode($geo, true);
            if ($data['status'] === 'success') {
                $result[] = ['label' => 'Country', 'value' => $data['country'] ?? 'Unknown'];
                $result[] = ['label' => 'Region', 'value' => $data['regionName'] ?? 'Unknown'];
                $result[] = ['label' => 'City', 'value' => $data['city'] ?? 'Unknown'];
                $result[] = ['label' => 'ISP', 'value' => $data['isp'] ?? 'Unknown'];
                $result[] = ['label' => 'Organization', 'value' => $data['org'] ?? 'Unknown'];
            }
        }
        
        respond(true, $result, '', "IP Information for {$ip}");
        break;

    default:
        respond(false, [], 'Invalid action');
}
