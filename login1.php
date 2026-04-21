<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<style>
body{
    font-family: Arial, sans-serif;
    background: #f4f6f9;
}

.login-box{
    width: 350px;
    margin: 120px auto;
    padding: 25px;
    background: white;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    border-radius: 8px;
}

h2{
    text-align:center;
    margin-bottom:20px;
}

label{
    display:block;
    margin-bottom:5px;
}

input{
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:4px;
    box-sizing:border-box;
}

button{
    width:100%;
    padding:10px;
    background:#007BFF;
    color:white;
    border:none;
    border-radius:4px;
    cursor:pointer;
}

button:hover{
    background:#0056b3;
}
</style>

</head>
<body>

<div class="login-box">
<h2>Login</h2>

<form action="check.php" method="POST">

<label>Username</label>
<input type="text" name="username" required>

<label>Password</label>
<input type="password" name="password" required>

<button type="submit">Login</button>

</form>
</div>

</body>
</html>