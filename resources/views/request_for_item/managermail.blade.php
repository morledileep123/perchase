<!DOCTYPE html>
<html>
<head>
    <title>New Request For Purchase</title>
</head>
<body>
 <div>
  <h6>Hi {{ $uname }} ! <h6></br>
    <p>User {{ Auth::user()->name }} Requested for Item On Date : {{date('Y-m-d')}}</p></br>
    <p>Please go through this link : <a href="http://www.laxyo.org" >Here</a>

 </div>
</body>
</html>