from flask import Flask, render_template, request, jsonify
import json
import os

app = Flask(__name__)

DATA_FILE = '/app/data/progress.json'

FLAGS = {
    "recon": {
        "name": "Recon & Enumeration",
        "flags": {
            "FLAG{ftp_anon_access_granted}": {"name": "FTP Anonymous Access", "difficulty": "easy", "hint": "Try connecting to FTP without credentials"},
            "FLAG{ssh_brute_easy_win}": {"name": "SSH Weak Credentials", "difficulty": "easy", "hint": "Common usernames, common passwords"},
            "FLAG{robots_reveal_secrets}": {"name": "Robots.txt Secrets", "difficulty": "easy", "hint": "What do web crawlers read first?"},
            "FLAG{git_dumped_exposed}": {"name": "Git Repository Exposed", "difficulty": "medium", "hint": "Version control can leak secrets"},
            "FLAG{directory_listing_open}": {"name": "Directory Listing", "difficulty": "easy", "hint": "Browse to /backup/"},
            "FLAG{env_file_leaked}": {"name": "Environment File Exposed", "difficulty": "easy", "hint": "Developers often forget sensitive files"},
            "FLAG{sql_dump_found}": {"name": "SQL Dump Found", "difficulty": "easy", "hint": "Check the backup directory"},
            "FLAG{phpinfo_exposed}": {"name": "PHPInfo Exposed", "difficulty": "easy", "hint": "A classic file that reveals too much"}
        }
    },
    "injection": {
        "name": "Injection Attacks",
        "flags": {
            "FLAG{sqli_login_bypassed}": {"name": "SQL Injection - Login Bypass", "difficulty": "easy", "hint": "Classic authentication bypass"},
            "FLAG{sqli_union_extracted}": {"name": "SQL Injection - UNION", "difficulty": "medium", "hint": "Extract data from other tables"},
            "FLAG{sqli_blind_boolean}": {"name": "SQL Injection - Blind Boolean", "difficulty": "medium", "hint": "True vs False responses differ"},
            "FLAG{cmd_injection_pwned}": {"name": "Command Injection", "difficulty": "easy", "hint": "The ping tool doesn't sanitize input"},
            "FLAG{cmd_filter_bypassed}": {"name": "Command Injection - Filter Bypass", "difficulty": "medium", "hint": "Some characters are filtered, but not all"}
        }
    },
    "auth": {
        "name": "Authentication & Session",
        "flags": {
            "FLAG{default_creds_admin}": {"name": "Default Credentials", "difficulty": "easy", "hint": "admin:admin is a classic"},
            "FLAG{bruteforce_no_limit}": {"name": "No Rate Limiting", "difficulty": "easy", "hint": "Try many passwords quickly"},
            "FLAG{reset_token_predictable}": {"name": "Predictable Reset Token", "difficulty": "medium", "hint": "How is the token generated?"},
            "FLAG{remember_me_decoded}": {"name": "Weak Remember Me Cookie", "difficulty": "easy", "hint": "Decode the cookie value"},
            "FLAG{cookie_httponly_missing}": {"name": "Cookie Not HttpOnly", "difficulty": "medium", "hint": "Can JavaScript access the cookie?"}
        }
    },
    "access_control": {
        "name": "Broken Access Control",
        "flags": {
            "FLAG{idor_orders_exposed}": {"name": "IDOR - Orders", "difficulty": "easy", "hint": "Change the order ID in the URL"},
            "FLAG{idor_profile_leak}": {"name": "IDOR - User Profiles", "difficulty": "easy", "hint": "Access other users' profiles"},
            "FLAG{idor_invoice_stolen}": {"name": "IDOR - Invoices", "difficulty": "easy", "hint": "Download other users' invoices"},
            "FLAG{forced_browsing_debug}": {"name": "Forced Browsing - Debug", "difficulty": "medium", "hint": "Admin panel has hidden pages"},
            "FLAG{api_no_authentication}": {"name": "API Without Auth", "difficulty": "easy", "hint": "The API doesn't check who you are"},
            "FLAG{mass_assignment_admin}": {"name": "Mass Assignment", "difficulty": "medium", "hint": "Can you add extra fields to the request?"}
        }
    },
    "xss": {
        "name": "Cross-Site Scripting",
        "flags": {
            "FLAG{xss_reflected_basic}": {"name": "Reflected XSS - Basic", "difficulty": "easy", "hint": "The search parameter isn't sanitized"},
            "FLAG{xss_stored_comment}": {"name": "Stored XSS - Comments", "difficulty": "easy", "hint": "Leave a malicious review"},
            "FLAG{xss_stored_username}": {"name": "Stored XSS - Username", "difficulty": "medium", "hint": "Your username appears in many places"}
        }
    },
    "file": {
        "name": "File Vulnerabilities",
        "flags": {
            "FLAG{upload_php_direct}": {"name": "File Upload - PHP Shell", "difficulty": "easy", "hint": "Upload a .php file as avatar"},
            "FLAG{upload_double_ext}": {"name": "File Upload - Double Extension", "difficulty": "medium", "hint": "shell.php.jpg might work"},
            "FLAG{lfi_passwd_read}": {"name": "LFI - /etc/passwd", "difficulty": "medium", "hint": "The page parameter includes files"},
            "FLAG{path_traversal_shadow}": {"name": "Path Traversal", "difficulty": "medium", "hint": "Download sensitive files"}
        }
    },
    "business": {
        "name": "Business Logic",
        "flags": {
            "FLAG{logic_negative_price}": {"name": "Negative Price", "difficulty": "easy", "hint": "What if the price is negative?"},
            "FLAG{logic_coupon_reuse}": {"name": "Coupon Reuse", "difficulty": "easy", "hint": "Use the same coupon twice"},
            "FLAG{logic_free_shipping}": {"name": "Free Shipping Bypass", "difficulty": "medium", "hint": "Remove the shipping field"}
        }
    },
    "other": {
        "name": "Other Vulnerabilities",
        "flags": {
            "FLAG{ssrf_internal_access}": {"name": "SSRF", "difficulty": "medium", "hint": "The image proxy can fetch internal URLs"},
            "FLAG{csrf_password_changed}": {"name": "CSRF - Password Change", "difficulty": "medium", "hint": "No CSRF token on password change"},
            "FLAG{clickjacking_framed}": {"name": "Clickjacking", "difficulty": "easy", "hint": "Can you iframe the site?"}
        }
    },
    "privesc": {
        "name": "Privilege Escalation",
        "flags": {
            "FLAG{sudo_privesc}": {"name": "Sudo Misconfiguration", "difficulty": "hard", "hint": "Check sudo -l after getting shell"},
            "FLAG{suid_exploit}": {"name": "SUID Binary Exploit", "difficulty": "hard", "hint": "Find SUID binaries with find"},
            "FLAG{crypto_md5_cracked}": {"name": "MD5 Password Crack", "difficulty": "medium", "hint": "Crack the hashes from the database dump"}
        }
    }
}

def load_progress():
    if os.path.exists(DATA_FILE):
        with open(DATA_FILE, 'r') as f:
            return json.load(f)
    return {"found": []}

def save_progress(data):
    os.makedirs(os.path.dirname(DATA_FILE), exist_ok=True)
    with open(DATA_FILE, 'w') as f:
        json.dump(data, f)

@app.route('/')
def index():
    progress = load_progress()
    total_flags = sum(len(cat["flags"]) for cat in FLAGS.values())
    found_count = len(progress["found"])
    
    categories_progress = {}
    for cat_id, cat_data in FLAGS.items():
        cat_found = sum(1 for flag in cat_data["flags"] if flag in progress["found"])
        cat_total = len(cat_data["flags"])
        categories_progress[cat_id] = {
            "name": cat_data["name"],
            "found": cat_found,
            "total": cat_total,
            "percentage": int((cat_found / cat_total) * 100) if cat_total > 0 else 0
        }
    
    return render_template('index.html', 
                         flags=FLAGS, 
                         progress=progress,
                         total_flags=total_flags,
                         found_count=found_count,
                         categories_progress=categories_progress)

@app.route('/submit', methods=['POST'])
def submit_flag():
    flag = request.form.get('flag', '').strip()
    progress = load_progress()
    
    for cat_id, cat_data in FLAGS.items():
        if flag in cat_data["flags"]:
            if flag not in progress["found"]:
                progress["found"].append(flag)
                save_progress(progress)
                return jsonify({
                    "success": True, 
                    "message": f"Correct! You found: {cat_data['flags'][flag]['name']}",
                    "category": cat_data["name"],
                    "new": True
                })
            else:
                return jsonify({
                    "success": True, 
                    "message": "Flag already submitted!",
                    "new": False
                })
    
    return jsonify({"success": False, "message": "Invalid flag"})

@app.route('/reset', methods=['POST'])
def reset_progress():
    save_progress({"found": []})
    return jsonify({"success": True, "message": "Progress reset!"})

@app.route('/hint/<flag>')
def get_hint(flag):
    for cat_data in FLAGS.values():
        if flag in cat_data["flags"]:
            return jsonify({"hint": cat_data["flags"][flag]["hint"]})
    return jsonify({"hint": "No hint available"})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=False)
