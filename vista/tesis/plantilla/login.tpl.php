
<!DOCTYPE html>
<html class=" ">
    <head>
       
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>{sitename}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="{autor}" name="author" />

        <link rel="shortcut icon" href="{siteurl}/vista/{vista}/plantilla/images/favicon.png" type="image/x-icon" />        
        
        <link href="{siteurl}/vista/{vista}/plantilla/plugins/pace.css" rel="stylesheet" type="text/css" media="screen"/>
        <link href="{siteurl}/vista/{vista}/plantilla/plugins/perfect-scrollbar.css" rel="stylesheet" type="text/css"/>
        <link href="{siteurl}/vista/{vista}/plantilla/plugins/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="{siteurl}/vista/{vista}/plantilla/plugins/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="{siteurl}/vista/{vista}/plantilla/plugins/orange.css" rel="stylesheet" type="text/css" media="screen"/>
        
        <link href="{siteurl}/vista/{vista}/plantilla/fonts/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
        
        <link href="{siteurl}/vista/{vista}/plantilla/css/animate.min.css" rel="stylesheet" type="text/css"/>
        <link href="{siteurl}/vista/{vista}/plantilla/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="{siteurl}/vista/{vista}/plantilla/css/responsive.css" rel="stylesheet" type="text/css"/>
        
    </head>
    
    <body class=" login_page">


        <div class="login-wrapper">
            <div id="login" class="login loginpage col-lg-offset-4 col-lg-4 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-0 col-xs-12">
                <h1><a href="{siteurl}" title="{sitename}" tabindex="-1">{sitename}</a></h1>

                <form name="loginform" id="loginform" action="{siteurl}/administracion/login" method="post">
                    <p>
                        <label for="user_login">Email<br />
                            <input type="text" name="email" id="user_login" class="input" placeholder="miemail@servidor.com" required/>
                        </label>
                    </p>
                    <p>
                        <label for="user_pass">Password<br />
                            <input type="password" name="password" id="user_pass" class="input" placeholder="****************" required />
                        </label>
                    </p> 
                    
                    <p class="submit">
                        <input type="submit" name="ingresar" id="submit" class="btn btn-orange btn-block" value="Acceder" />
                        <input type="hidden" name="login" value="trueTruetrueTrue">
                    </p>
                </form>

                 <p id="nav">
                    <a class="pull-left" href="{siteurl}/administracion/password-olvidado" title="Contraseña olvidada?">Contraseña Olvidada?</a>
                    <a class="pull-right" href="{siteurl}/administracion/registrarme" title="Registrarme">Registrarme</a>
                </p>

            </div>
        </div>
        
        <script src="{siteurl}/vista/{vista}/plantilla/js/jquery-1.11.2.min.js" type="text/javascript"></script> 
        <script src="{siteurl}/vista/{vista}/plantilla/js/jquery.easing.min.js" type="text/javascript"></script> 
        <script src="{siteurl}/vista/{vista}/plantilla/js/bootstrap.min.js" type="text/javascript"></script> 
        <script src="{siteurl}/vista/{vista}/plantilla/js/pace.min.js" type="text/javascript"></script>  
        <script src="{siteurl}/vista/{vista}/plantilla/js/perfect-scrollbar.min.js" type="text/javascript"></script> 
        <script src="{siteurl}/vista/{vista}/plantilla/js/viewportchecker.js" type="text/javascript"></script>  
        <script src="{siteurl}/vista/{vista}/plantilla/js/icheck.min.js" type="text/javascript"></script>
        <script src="{siteurl}/vista/{vista}/plantilla/js/scripts.js" type="text/javascript"></script> 
        <script src="{siteurl}/vista/{vista}/plantilla/js/jquery.sparkline.min.js" type="text/javascript"></script>
        <script src="{siteurl}/vista/{vista}/plantilla/js/chart-sparkline.js" type="text/javascript"></script>

    </body>
</html>