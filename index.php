<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Fixture</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        

        <style>
            *{
                padding: 0;
                margin: 0;
                font-family: sans-serif;
                box-sizing: border-box;
            }
            #container{
                height: 100vh;
                width: 100%;
                position: relative;
                top: 0;
                left: 0;
                background: #e9e9e9
            }
            #container #logo{
                position:absolute;
                left: 8%;
                top: 30px;
                font-size: 30px;
                font-weight: bold;
                z-index: 997;
                color: rgb(33, 33, 80)

            }
            #navbar{
                height: 90px;
                width: 100%;
                position:absolute;
                top: 0;
                left: 0;
                background: rgb(248, 247, 246);
                display: grid;
                place-items: center;
            }
            #navbar ul{
                list-style: none;
                display: flex;
                flex-direction: row;
            }
            #navbar ul li{
                font-size: 20px;
                margin: 0 45px;
            }
            #navbar ul a{
                text-decoration: none;
                color: rgb(247, 243, 200);
                position: absolute;
                top: 30px;
                right: 15px;
                display: grid;
                place-items: center;
                background: rgb(42, 29, 100);
                border-radius: 50px 15px;

            }
            #content{
                position: absolute;
                top:30%;
                left: 10%;
                z-index: 996;  
            }
            #content h2{
                font-size: 260px;
                color: #313286;
            }
            #content h2:hover{
                color: #030211;
                transition: 0.5s;
            }

            #content h4{
                font-size: 60px;

            }
            #content h4:hover{
                color: #1e1764;
            }


        </style>
    </head>
    <body >


        <div id="container">
           
            <div id="logo">Tanzania Premier League Board (TPLB)</div>
            <div id="navbar">
               <ul>
                 <li><a href="admin/admin_login.php" id="login">Login</a></li>
                
                    <li>
                
           
                  </li>
               </ul>
            </div>
        </div>
        <div id="content">
            <h2>FIXTURE</h2>
            <h4>SOFTWARE</h4>
        </div>


    </body>
</html>
