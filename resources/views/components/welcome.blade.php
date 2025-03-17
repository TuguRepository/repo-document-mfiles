<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to M-Files</title>
    <style>
        .mfiles-container {
            max-width: 300px;
            margin: auto;
            padding: 20px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:disabled {
            background: #ccc;
        }
        .error {
            color: red;
            font-size: 12px;
            margin-top: -5px;
        }
    </style>
</head>
<body>

<div class="mfiles-container">
    <div class="mfiles-login" id="login-form">
        <h3>Login to M-Files</h3>
        <input type="text" id="UID" placeholder="UID" maxlength="3">
        <span class="error" id="uid-error"></span>
        <input type="password" id="Password" placeholder="Password">
        <button id="login-button" disabled>Login</button>
    </div>
</div>

<script>
    const uidInput = document.getElementById("UID");
    const passwordInput = document.getElementById("Password");
    const loginButton = document.getElementById("login-button");
    const uidError = document.getElementById("uid-error");

    function validateUID() {
        const uidValue = uidInput.value.trim();
        if (uidValue.length < 3) {
            uidError.textContent = "UID harus tepat 3 huruf.";
            loginButton.disabled = true;
        } else if (uidValue.length > 3) {
            uidError.textContent = "UID tidak boleh lebih dari 3 huruf.";
            loginButton.disabled = true;
        } else {
            uidError.textContent = "";
            loginButton.disabled = false;
        }
    }

    uidInput.addEventListener("input", validateUID);

    loginButton.addEventListener("click", function() {
        // Simpan UID dan Password (sementara, tanpa backend)
        const uidValue = uidInput.value.trim();
        const passwordValue = passwordInput.value.trim();

        if (uidValue.length === 3 && passwordValue !== "") {
            // Redirect ke halaman dashboard STK Laravel
            window.location.href = "/dashboardstk/stk/database";  // Sesuaikan dengan route Laravel-mu
        } else {
            alert("Pastikan UID 3 huruf dan password terisi!");
        }
    });
</script>

</body>
</html>
