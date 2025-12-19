# 💡 Shopz Hints

Progressive hints for each vulnerability category. Try to solve challenges yourself first!

---

## 🔍 Recon & Enumeration

<details>
<summary>FTP Anonymous Access</summary>

**Hint 1:** FTP servers sometimes allow anonymous login  
**Hint 2:** Try `ftp localhost` with username `anonymous`  
**Hint 3:** Check the /pub directory
</details>

<details>
<summary>SSH Weak Credentials</summary>

**Hint 1:** Common usernames like admin, dev, backup  
**Hint 2:** Passwords are often simple: admin123, password, etc.  
**Hint 3:** Use hydra for automated testing
</details>

<details>
<summary>Robots.txt</summary>

**Hint 1:** What file tells search engines what NOT to index?  
**Hint 2:** `curl http://localhost/robots.txt`
</details>

<details>
<summary>Git Exposed</summary>

**Hint 1:** Developers sometimes forget to remove version control  
**Hint 2:** Check `/.git/config`  
**Hint 3:** Tools like git-dumper can extract the whole repo
</details>

---

## 💉 Injection

<details>
<summary>SQL Injection - Login</summary>

**Hint 1:** Classic SQL injection in the login form  
**Hint 2:** What happens if you add a quote?  
**Hint 3:** `admin' OR '1'='1' --`
</details>

<details>
<summary>SQL Injection - Search</summary>

**Hint 1:** The search function is vulnerable  
**Hint 2:** Try UNION-based injection  
**Hint 3:** `' UNION SELECT 1,2,3,4,5,6,7--`
</details>

<details>
<summary>Command Injection</summary>

**Hint 1:** The admin panel has a ping tool  
**Hint 2:** Linux commands can be chained  
**Hint 3:** `; whoami` or `| cat /etc/passwd`
</details>

---

## 🔐 Authentication

<details>
<summary>Default Credentials</summary>

**Hint 1:** Admin panels often have default passwords  
**Hint 2:** admin:admin is very common  
**Hint 3:** Check the admin panel on port 8080
</details>

<details>
<summary>Password Reset Token</summary>

**Hint 1:** How is the reset token generated?  
**Hint 2:** It's based on email and timestamp  
**Hint 3:** MD5(email + timestamp) is predictable
</details>

<details>
<summary>Remember Me Cookie</summary>

**Hint 1:** Check the remember_me cookie value  
**Hint 2:** It looks like base64  
**Hint 3:** Decode it: `echo "xxx" | base64 -d`
</details>

---

## 🚪 Access Control

<details>
<summary>IDOR - Orders</summary>

**Hint 1:** Look at the URL when viewing your order  
**Hint 2:** Change the ID parameter  
**Hint 3:** `/orders.php?id=1`, then try id=2, id=3...
</details>

<details>
<summary>API Without Auth</summary>

**Hint 1:** The API endpoints don't check authentication  
**Hint 2:** Try `/api/users.php`  
**Hint 3:** All user data is exposed
</details>

---

## 🎭 XSS

<details>
<summary>Reflected XSS</summary>

**Hint 1:** The search parameter is reflected in the page  
**Hint 2:** Try `<script>alert(1)</script>`  
**Hint 3:** Search for: `<img src=x onerror=alert(1)>`
</details>

<details>
<summary>Stored XSS</summary>

**Hint 1:** Comments on products are stored  
**Hint 2:** Input isn't sanitized  
**Hint 3:** Post a comment with JavaScript
</details>

---

## 📁 File Vulnerabilities

<details>
<summary>File Upload</summary>

**Hint 1:** The avatar upload has no extension filter  
**Hint 2:** Try uploading a .php file  
**Hint 3:** Simple webshell: `<?php system($_GET['cmd']); ?>`
</details>

<details>
<summary>LFI</summary>

**Hint 1:** The page.php uses include()  
**Hint 2:** Path traversal: `../../../etc/passwd`  
**Hint 3:** `/page.php?page=../../../etc/passwd`
</details>

---

## 💰 Business Logic

<details>
<summary>Negative Price</summary>

**Hint 1:** Intercept the checkout request  
**Hint 2:** The price field is in the POST data  
**Hint 3:** Change total to a negative value
</details>

<details>
<summary>Coupon Reuse</summary>

**Hint 1:** Apply a coupon code  
**Hint 2:** Clear your cart, add items again  
**Hint 3:** The same coupon works multiple times
</details>

---

## ⬆️ Privilege Escalation

<details>
<summary>Sudo Misconfiguration</summary>

**Hint 1:** After getting shell access, run `sudo -l`  
**Hint 2:** Some users can run commands as root  
**Hint 3:** GTFOBins has bypass techniques
</details>

<details>
<summary>SUID Binary</summary>

**Hint 1:** Find SUID binaries: `find / -perm -4000 2>/dev/null`  
**Hint 2:** Some binaries can be exploited  
**Hint 3:** Check GTFOBins for exploitation methods
</details>

---

## 🔑 Default Credentials

| Service | Username | Password |
|---------|----------|----------|
| Admin Panel | admin | admin |
| SSH | admin | admin123 |
| SSH | dev | dev |
| MySQL | root | root |
| FTP | anonymous | (empty) |

---

*Use these hints wisely - the real learning comes from struggling!*
