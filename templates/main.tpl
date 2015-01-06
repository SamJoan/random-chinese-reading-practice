<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Random Chinese Reading Practice</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  </head>
  <body>
  <div class="container">
      <form method="POST">
        <h2 class="form-signin-heading">Random Chinese Reading Practice</h2>
        <p>Please select a difficulty</p>
        <select onchange="this.form.submit()" name="category">
          <option></option>
          <option value='beginner'>Beginner</option>
          <option value='intermediate'>Intermediate</option>
          <option value='advanced'>Advanced</option>
        </select>
      </form>
    </div>
  </body>
</html>
