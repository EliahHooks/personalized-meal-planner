<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meal Planner | Welcome</title>
  <style>
      
      /* remove the default browser settings*/
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    /* Settings for the body*/
    body 
    {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f5f5;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Main container settings*/
    .container 
    {
      background: white;
      padding: 2.5rem;
      border-radius: 12px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      text-align: center;
      max-width: 400px;
      width: 90%;
    }

    /* h1 header settings*/
    h1 
    {
      color: #333;
      margin-bottom: 1rem;
      font-size: 1.8rem;
      font-weight: 600;
    }

    /* Paragraph settings*/
    p 
    {
      color: #666;
      margin-bottom: 2rem;
      font-size: 1.1rem;
    }

    /* Customization for the create account button*/
    button 
    {
      background: green;
      color: white;
      border: none;
      padding: 12px 30px;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    /* Hover effect for the create account button*/
    button:hover 
    {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4); 
    }

    button:active 
    {
      transform: translateY(0);
    }
    
  </style>
</head>
<body>
  <div class="container">
    <h1>Welcome to the Personalized Meal Planner</h1>
    <p>New here?</p>
    <form action="register.html" method="get">
      <button type="submit">Create an Account</button>
    </form>
  </div>
</body>
</html>