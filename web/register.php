<!DOCTYPE html>
<html lang="en">
    <head>
      <?php require_once 'common/head.php.inc'; ?>
      <link rel="stylesheet" type="text/css" href="css/register.css">
      <title>Dinen - Sign Up</title>
    </head>
    <body>
        <?php require_once 'common/navbar.php.inc'; ?>
        <div class="container">
            <div class="row main">
                <!-- <div class="panel-heading">
                    <div class="panel-title text-center">
                        <h1 class="title"><a href="index.php">Dinen</a> - Register</h1>
                        <hr />
                    </div>
                </div> -->
                <div class="main-login main-center">
                    <div class="panel-heading">
                        <div class="panel-title text-center">
                            <h1 class="title">Join Dinen</h1>
                            <hr />
                        </div>
                    </div>
                    <div id="msgDiv"> </div>
                    <form id="registerForm" class="form-horizontal" method="post" onsubmit="return register()">

                        <div class="form-group">
                            <label for="name" class="cols-sm-2 control-label">Your Name</label>
                            <div class="cols-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control" name="name" id="name" required placeholder="Enter your Name"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="cols-sm-2 control-label">Your Email</label>
                            <div class="cols-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
                                    <input type="email" class="form-control" name="email" id="email" required placeholder="Enter your Email"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="cols-sm-2 control-label">Password</label>
                            <div class="cols-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                                    <input type="password" class="form-control" name="password" id="password" minlength="8" required placeholder="Enter your Password"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation"
                                   class="cols-sm-2 control-label">Confirm Password</label>
                            <div class="cols-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                                    <input type="password" class="form-control" name="password_confirmation"
                                           id="password_confirmation" minlength="8" required placeholder="Confirm your Password"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <button type="submit" class="btn btn-primary btn-lg btn-block login-button">Register</button>
                        </div>
                        <div class="login-register">
                            <a href="login.php">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
