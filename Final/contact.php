<!DOCTYPE html>
<html>
<head>
    <title>Contact Us Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="contactstyle3.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');
        *{
            margin:0px;
            padding:0px;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        h1{
            color: #fff;
        }

        body{
            background:url(bg.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            width:100%;
        }
        .contact-form{
            width:85%;
            max-width: 600px;
            background: #a9a9a9;
            position: absolute;
            top:50%;
            left:50%;
            transform: translate(-50%, -50%);
            padding:30px 40px;
            border-radius: 8px;
            text-align: center;
        }
        .form{
            border:1px solid #fff;
            margin:7px 0px;
            padding:6px 20px;
            border-radius: 8px;
        }
        .contact-form h1{
            font-weight: 500;
            margin-top: 0px;
        }
        .form label{
            font-size: 13px;
            color:#fff;
            display: block;
            text-align: left;
            text-transform: uppercase;
        }
        .form input, .form textarea{
            width:100%;
            font-size:19px;
            margin-top: 5px;
            border:none;
            background:none;
            outline:none;
        }
        .button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="contact-form">
        <h1>Contact Us</h1>
        <form action="https://formsubmit.co/mmuecho@outlook.my" method="POST">
            <div class="form">
                <label>User Name :</label>
                <input type="text" name="username" placeholder="Enter Your Name" required>
            </div>
            <div class="form">
                <label>Email :</label>
                <input type="email" name="email" placeholder="Enter Your e-Mail" required>
            </div>
            <div class="form">
                <label>Phone Number :</label>
                <input type="text" name="phone" placeholder="Enter Your Phone Number">
            </div>
            <div class="form">
                <label>Message :</label>
                <textarea name="message" placeholder="Enter Your Message..." required></textarea>
            </div>
            <button type="submit" class="button">Send</button>
        </form>
    </div>

</body>
</html>
