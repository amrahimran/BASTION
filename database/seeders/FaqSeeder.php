<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            // General Cybersecurity FAQs
            [
                'question' => 'How do I create a strong password for my company accounts?',
                'answer' => 'A strong password keeps your company safe. Make sure your password is at least 12 characters long, uses upper and lower case letters, numbers, and symbols (like !@#$), and is unique for each account. Avoid obvious words like your name, birthday, or "password123". Consider using a password manager to safely store and generate passwords.',
                'category' => 'Password Security',
                'is_active' => true,
            ],
            [
                'question' => 'What should I do if I receive a suspicious email?',
                'answer' => 'If an email looks strange or unexpected, do not click any links or download attachments. Check the sender’s email carefully and make sure it is legitimate. Report it immediately to your IT or cybersecurity team. Suspicious emails are often phishing attacks trying to steal your login details.',
                'category' => 'Email Safety',
                'is_active' => true,
            ],
            [
                'question' => 'How do I know if a website is safe to use?',
                'answer' => 'Before entering your company information on a website, look for HTTPS in the address bar and a lock icon, check that the domain is correct, and avoid links from emails or pop-ups. Do not download files from untrusted websites. Safe browsing protects your company from malware and hackers.',
                'category' => 'Internet Safety',
                'is_active' => true,
            ],
            [
                'question' => 'What is two-factor authentication (2FA) and why should I use it?',
                'answer' => 'Two-factor authentication adds an extra layer of security beyond your password. After entering your password, you will also enter a code from your phone or email. This ensures that even if someone steals your password, they cannot log in without the code. Always enable 2FA on company accounts.',
                'category' => 'Account Security',
                'is_active' => true,
            ],
            [
                'question' => 'What should I do if I think my account has been hacked?',
                'answer' => 'If you suspect a hack, change your password immediately, enable two-factor authentication if not already active, report the incident to your IT team, and check for unusual activity. Acting quickly minimizes potential damage.',
                'category' => 'Incident Response',
                'is_active' => true,
            ],

            // Scan-Specific FAQs
            [
                'question' => 'What is Nmap Host Discovery?',
                'answer' => 'Nmap Host Discovery is a scan that finds which devices are currently active on your network. It helps identify live systems that may need monitoring or protection.',
                'category' => 'Nmap Scans',
                'is_active' => true,
            ],
            [
                'question' => 'What is a Basic Port Scan?',
                'answer' => 'A Basic Port Scan checks for common "doors" (network ports) that services use to communicate. This helps identify which services are open and potentially vulnerable to attack.',
                'category' => 'Nmap Scans',
                'is_active' => true,
            ],
            [
                'question' => 'What is OS Fingerprinting?',
                'answer' => 'OS Fingerprinting guesses what operating system a device is running. Knowing the OS helps the IT team identify vulnerabilities and apply the right security updates.',
                'category' => 'Nmap Scans',
                'is_active' => true,
            ],
            [
                'question' => 'What is Banner Grabbing?',
                'answer' => 'Banner Grabbing collects short information from network services, often including version numbers. This allows you to spot outdated software that may need updating.',
                'category' => 'Nmap Scans',
                'is_active' => true,
            ],
            [
                'question' => 'What is a Weak SSH Configuration Check?',
                'answer' => 'This scan checks if the SSH (secure login) service is using weak settings, such as outdated encryption or easily guessable configurations, which could allow attackers to access systems.',
                'category' => 'Nmap Scans',
                'is_active' => true,
            ],
            [
                'question' => 'What is FTP Anonymous Login?',
                'answer' => 'FTP Anonymous Login checks whether the FTP server allows anyone to log in without a password. Open anonymous access can allow unauthorized people to view or modify files.',
                'category' => 'Nmap Scans',
                'is_active' => true,
            ],
            [
                'question' => 'What is SMB Share Scan?',
                'answer' => 'SMB Share Scan finds shared folders on Windows-style file shares. This helps identify sensitive data that may be publicly accessible by mistake.',
                'category' => 'Nmap Scans',
                'is_active' => true,
            ],
            [
                'question' => 'What are Nmap NSE Scripts?',
                'answer' => 'Nmap NSE (Nmap Scripting Engine) scripts run deeper checks to spot known issues in devices and services. It provides more detailed information about potential security problems.',
                'category' => 'Nmap Scans',
                'is_active' => true,
            ],
            [
                'question' => 'What is Nikto Web Scan?',
                'answer' => 'Nikto Web Scan checks web servers for common misconfigurations, outdated software, or known issues. This helps protect your websites from attackers.',
                'category' => 'Web Scans',
                'is_active' => true,
            ],
            [
                'question' => 'What is SSL/TLS Scan?',
                'answer' => 'SSL/TLS Scan inspects the security of your site’s encryption settings (HTTPS). Strong encryption protects company data and communications from attackers.',
                'category' => 'Web Scans',
                'is_active' => true,
            ],
            [
                'question' => 'What are Docker Misconfigurations?',
                'answer' => 'This scan searches for common insecure Docker or container setups. Misconfigured containers can let attackers access company systems or sensitive data.',
                'category' => 'Container Security',
                'is_active' => true,
            ],
            [
                'question' => 'What is Firewall Status Check?',
                'answer' => 'Firewall Status Scan checks whether firewall rules are present and active. Firewalls help block unauthorized access to company systems.',
                'category' => 'Network Security',
                'is_active' => true,
            ],
            [
                'question' => 'What is Passive Network Sniffing?',
                'answer' => 'Passive Network Sniffing listens for unencrypted network traffic without changing anything. It helps identify sensitive data being transmitted insecurely.',
                'category' => 'Network Security',
                'is_active' => true,
            ],
            [
                'question' => 'What is DNS Misconfiguration Check?',
                'answer' => 'DNS Misconfiguration Check looks for mistakes in how company domain names are set up. Incorrect settings can allow attackers to redirect traffic or intercept emails.',
                'category' => 'Network Security',
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
