<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/index.css')}}" />
    <title>MeetMe - Welcome Page</title>
</head>
<body>
    <header class="section">
        <div class="logo">MeeTMe</div>
        <div class="btns">
            <button type="button" id="signup" class="btn btn-dark border-light active">Sign Up</button>
            <button type="button" id="login" class="btn btn-dark border-light">Login</button>
        </div>
    </header>
    <main class="section">
        <div class="signup-form">
            <h3 class="title">Join Today!</h3>
            <form action="/signup">
                <div class="form-row">
                    <label for="fname">First name:</label>
                    <input type="text" name="fname" id="fname" minlength="3" required/>
                </div>
                <div class="form-row">
                    <label for="lname">Last name:</label>
                    <input type="text" name="lname" id="lname" minlength="3" required/>
                </div>
                <div class="form-row">
                    <label for="email">Email address:</label>
                    <input type="email" name="email" id="email" minlength="8" required/>
                </div>
                <div class="form-row">
                    <label for="dob">Date of birth:</label>
                    <input type="date" name="dob" id="dob" required/>
                </div>
                <div class="form-row">
                    <label for="pass1">Password:</label>
                    <input type="password" name="pass1" id="pass1" minlength="6" required/>
                </div>
                <div class="form-row">
                    <label for="pass2">Repeat password:</label>
                    <input type="password" name="pass2" id="pass2" minlength="6" required/>
                </div>
                <div class="form-row" style="justify-content: center;">
                    <input type="submit" style="padding: .5rem 3rem; margin-left: 1rem;" class="btn btn-dark" value="Join" /> or <button>Signup</button>
                </div>
            </form>
        </div>
        <div class="login-form hide">
            <form action="/login">
                <h3 class="title">Login</h3>
                <div class="form-row">
                    <label for="lemail">Email address:</label>
                    <input type="email" name="lemail" id="lemail" required/>
                </div>
                <div class="form-row">
                    <label for="lpass">Password:</label>
                    <input type="password" name="lpass" id="lpass" required/>
                </div>
                <div class="alert alert-danger hide">
                    Wrong credentials, please try again!
                </div>
                <div class="form-row" style="justify-content: center;">
                    <input type="submit" class="btn btn-dark" value="Login" />
                </div>
            </form>
        </div>
    </main>
</body>
</html>