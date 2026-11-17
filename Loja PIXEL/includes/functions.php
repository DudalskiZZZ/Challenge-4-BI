<?php
function db(): PDO {static $pdo=null;if($pdo)return $pdo;$host=$_ENV['DB_HOST']??'127.0.0.1';$name=$_ENV['DB_NAME']??'loja_pixel';$user=$_ENV['DB_USER']??'root';$pass=$_ENV['DB_PASS']??'';$charset='utf8mb4';$pdo=new PDO("mysql:host=$host;dbname=$name;charset=$charset",$user,$pass,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);return $pdo;}
function e($v):string{return htmlspecialchars((string)$v,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
function money($v):string{return 'R$ '.number_format((float)$v,2,',','.');}
function image_url($path):string{$path=trim((string)$path);return $path!==''?$path:'assets/img/placeholder.svg';}
function csrf_token():string{if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf'];}
function csrf_field():string{return '<input type="hidden" name="csrf_token" value="'.e(csrf_token()).'">';}
function verify_csrf():void{if(!hash_equals(csrf_token(),(string)($_POST['csrf_token']??''))){http_response_code(403);exit('Solicitação inválida. Atualize a página e tente novamente.');}}
function require_login():void{if(empty($_SESSION['usuario'])){header('Location: login.php');exit;}}
function require_admin():void{require_login();if(($_SESSION['usuario']['perfil']??'')!=='admin'){http_response_code(403);exit('Acesso negado.');}}
function cart_items():array{$cart=$_SESSION['carrinho']??[];if(!$cart)return [];$ids=array_values(array_filter(array_map('intval',array_keys($cart))));if(!$ids)return [];$marks=implode(',',array_fill(0,count($ids),'?'));$s=db()->prepare("SELECT id,nome,preco,estoque,imagem FROM produtos WHERE ativo=1 AND id IN ($marks)");$s->execute($ids);$out=[];foreach($s->fetchAll() as $p){$qty=min((int)$cart[$p['id']],(int)$p['estoque']);if($qty<1)continue;$p['quantidade']=$qty;$p['subtotal']=$qty*(float)$p['preco'];$out[]=$p;}return $out;}
function cart_total():float{return array_sum(array_column(cart_items(),'subtotal'));}
function is_admin():bool{return ($_SESSION['usuario']['perfil']??'')==='admin';}
