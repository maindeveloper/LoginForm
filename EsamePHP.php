<!DOCTYPE html>
<html>
  <head>
    <title>° Home °</title>
    <link href="stile1.css" rel="stylesheet" type="text/css"/>
  </head>
<body>
<?php

  //Codice che permette la stampa degli errori sul browser
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL | E_STRICT);

  $nuovoUtente = "
  <form action=\"EsamePHP.php\" method=\"post\">
  <input type=\"hidden\" name=\"seenform\" value=\"y\">
  <br>
  <div id=container>
  <label><h2><em>¨ Benvenuto sul sito ¨</em></h2></label>
  <div>
  <br>
  <h3>Accedi o registrati</h3>
  <input type=\"email\" name=\"email\" value=\"\" placeholder=\"Email\" required><br><br>
  <input type=\"password\" name=\"password\" value=\"\" placeholder=\"Password\" required><br><br>
  <button type=\"submit\" value=\"Accedi\" name=\"accedi\">Accedi</button>
  <button type=\"submit\" value=\"Registrati\" name=\"registrati\">Registrati</button>
  <br><br><br>
  </div>
  <label><i>©2017-2018</i></label>
  </div>
  </form>";

  $registrato = "
  <form><div>
  <label><h2>¨ Inserimento effettuato! ¨</h2></label>
  </div></form>";

  $accesso = "
  <form><div>
  <label><h2>¨ Bentornato, ci sei mancato! ¨</h2></label>
  <label><h3><em>¨ Carica l'ultimo salvataggio ¨</em></h3></label>
  </div></form>";

  $permessoNegato = "
  <form><div>
  <label><h2>¨ Email o password errati! ¨</h2></label>
  </div></form>";

  // MI CONNETTO AL DATABASE
  $mysqli=new mysqli();
  $mysqli->connect("nomeServer","nomeUtente","nomePassword","nomeDataBase");
  
  if($mysqli->connect_errno){
    print "Connessione al database fallita: ". $mysqli->connect_error.".";
    exit();
  }

// SE IL FORM E' VUOTO LO PROPONE
if(!isset($_POST['seenform'])){
  print $nuovoUtente;
}
//Altrimenti...
else{
  //se siamo in questo caso abbiamo riempito il form
  //e abbiamo fatto la submit sul bottone registrati
   if(isset($_POST['registrati'])){
      $inserimento=$mysqli->prepare("INSERT INTO nomeDataBase(email,password) VALUES(?,?)");
      $inserimento->bind_param('ss',$email,$password);
      $email=$_POST['email'];
      //$password=$_POST['password'];
      $password=hash('MD5',$_POST['password']);
      $inserimento->execute();
      print $registrato;
      $inserimento->close();
    }
    else{
      //se siamo in questo caso abbiamo riempito il form
      //e abbiamo fatto la submit sul bottone login
      $email=$_POST['email'];
      $password=hash('MD5',$_POST['password']);
      $ricerca=$mysqli->prepare("SELECT email,password FROM nomeDataBase WHERE email='$email' AND password='$password'");
      $ricerca->execute();
      $ricerca->bind_result($emailtrovata,$passwordtrovata);
      //se la ricerca va a buon fine, quindi la select trova qualcosa
      if($utente=$ricerca->fetch()){
        print $accesso;
      }
      //
      else{
        print $permessoNegato;
      }
      $ricerca->close();
    }
}
?>
</body>
</html>
