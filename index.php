<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <title>Autopost8r</title>
	<script src="https://vk.com/js/api/openapi.js?152" type="text/javascript"></script>
</head>
<body>
	<form action="./oauth/vkOauth.php" ><button>войти Через ВК</button></form><br>
	<form action="./oauth/vk.php" ><button>войти Через dddВК</button></form><br>
	<form action="./oauth/twitterOauth.php"><button>войти через Твиттер</button></form>
	<script type="text/javascript" src="//vk.com/js/api/openapi.js?152"></script>
<script type="text/javascript">
  VK.init({apiId: 6422262});
</script>

<!-- VK Widget -->
<div id="vk_auth"></div>
<script type="text/javascript">
  VK.Widgets.Auth("vk_auth", {"onAuth":"function(data) {alert('user '+data['uid']+' authorized');}"});
</script>
	
 <form action="textarea1.php" method="post">
    <p><b>Текст поста:</b></p>
    <p><textarea rows="10" cols="45" name="text"></textarea></p>
    <p><input type="submit" value="Отправить"></p>
  </form>
</body>
