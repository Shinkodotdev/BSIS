+-------------------------------------------------+
|            BARANGAY INFORMATION SYSTEM          |
+-------------------------------------------------+
______________________________________________________________________________________________________________________

📌 Database Connection Documentation
1. This project uses PHP PDO (PHP Data Objects) to connect securely to a MySQL database. PDO provides a flexible and secure way to handle database interactions.
_______________________________________________________________________________________________________________________

⚙️ How It Works
1. Host → The server where the database is running (usually localhost for local development).

2. Database Name → The name of your application’s database.

3. Username → The MySQL username with access to the database.

4. Password → The password for the database user.

5. The connection is wrapped in a try/catch block:

6. The script attempts to connect using the provided credentials.

7. If successful, PDO is initialized and ready to use.

8. If the connection fails, an exception is thrown and caught. The application stops execution to prevent further errors.


🔒 Security Best Practices
1. Do not hardcode credentials
    Store your database credentials in an environment file (.env) instead of inside PHP files.

2. Use a dedicated database user
    Avoid using the default root account. Create a separate MySQL user with only the necessary permissions (SELECT, INSERT, UPDATE, DELETE).

3. Hide detailed errors in production
    Instead of showing database errors directly to users, log them internally. Show only a generic failure message to the public.

4. Force secure PDO settings
    Enable PDO::ERRMODE_EXCEPTION → ensures errors are properly caught.

    Disable PDO::ATTR_EMULATE_PREPARES → ensures real prepared statements (prevents SQL injection).

    Use PDO::FETCH_ASSOC → avoids duplicate numeric indexes when fetching rows.
_______________________________________________________________________________________________________________________

On pages there is a dashboard.php but that is a role-agnostic main page which any role can access
🔐 PHP Auth Check Middleware

This file (auth_check.php) is a reusable authentication and authorization guard for protecting pages in your system. It ensures only logged-in users with the correct role and status can access certain pages.
📌 Features

✅ Starts a session only if it isn’t already active

✅ Redirects unauthenticated users to an unauthorized_page.php

✅ Role-based access control (e.g., only Admins or Officials)

✅ Status-based access control (e.g., only Approved/Verified users)

✅ Reusable on any page by including the file