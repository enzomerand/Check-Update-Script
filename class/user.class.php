<?php
class USER
{
    private $db;
 
    function __construct($db)
    {
        $this->db = $db;
    }
 
    public function login($user_email,$user_password)
    {
       try
       {
          $stmt = $this->db->prepare("SELECT * FROM cus_settings WHERE user_email=:user_email LIMIT 1");
          $stmt->execute(array(':user_email'=>$user_email));
          $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
          if($stmt->rowCount() > 0)
          {
             if(password_verify($user_password, $userRow['user_password']))
             {
                $_SESSION['user_session'] = $userRow['user_email'];
                return true;
             }
             else
             {
                return false;
             }
          }
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }
 
   public function is_loggedin()
   {
      if(isset($_SESSION['user_session']))
      {
         return true;
      }
   }
 
   public function redirect($url)
   {
       header("Location: $url");
   }
 
   public function logout()
   {
        session_destroy();
        unset($_SESSION['user_session']);
        return true;
   }
}
?>
