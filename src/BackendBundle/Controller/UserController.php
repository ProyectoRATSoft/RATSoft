<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\TblUser;
use BackendBundle\Entity\TblRubros;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;

class UserController extends Controller
{

  public function allAction() {
    // Cargo los servicios que voy a utilizar.
    $serializer = SerializerBuilder::create()->build();

    // Busco en la DB los usuarios existentes.
    $em = $this->getDoctrine()->getManager();
    $users = $em->getRepository("BackendBundle:TblUser")->findAll();

    $data = array(
      'status' => 'OK',
      'users' => $users
    );

    $jsonResponse = $serializer->serialize($data, 'json');
    return new Response($jsonResponse);
  }

  public function newAction(Request $request) {
    // Cargo los servicios que voy a utilizar.
    $helpers = $this->get('app.helpers');
    $serializer = SerializerBuilder::create()->build();

    $params = (object) [];
    $params->username = $request->request->get("username");
    $params->password = $request->request->get("password");
    $params->nombre = $request->request->get("nombre");
    $params->apellido = $request->request->get("apellido");
    $params->email = $request->request->get("email");
    $params->role = $request->request->get("role");

    // Por default $data devuelve un error generico.
    $data = array(
      'status' => 'ERROR',
      'msg' => 'El usuario no pudo ser creado.'
    );

  	 // Si $json no es null, procedo a crear el usuario.
    if ($params != null) {
      $username = (isset($params->username)) ? $params->username : null;
      $password = (isset($params->password)) ? $params->password : null;
      $nombre = (isset($params->nombre)) ? $params->nombre : null;
      $apellido = (isset($params->apellido)) ? $params->apellido : null;
      $email = (isset($params->email)) ? $params->email : null;
      $role = (isset($params->role)) ? $params->role : 1;
      $activo = 1;
      $createdAt = new \Datetime('now');

	     // Valido que el Email sea un realmente un email mediante Asserts.
      $emailContraint = new Assert\Email();
      $emailContraint->message = "Este email no es válido";
      $validate_email = $this->get('validator')->validate($email, $emailContraint);

      // Valido que ninguno de los datos del usuario sea null.
      if ($email != null && count($validate_email) == 0 && $password != null && $nombre != null && $apellido != null	&& $role != null) {
    	  // Instanciamos un objeto User y seteamos sus datos.
        $user = new TblUser();
        $user->setUsername($username);
        $user->setNombre($nombre);
        $user->setApellido($apellido);
        $user->setEmail($email);
        $user->setRole($role);
        $user->setActivo($activo);
        $user->setCreateDate($createdAt);

      	 // Cifrar contraseña
        $psw = hash('sha256', $password);
        $user->setPassword($psw);

      	 // Busco en la DB si existe un usuario con el email ingresado.
        $em = $this->getDoctrine()->getManager();
        $isset_user = $em->getRepository("BackendBundle:TblUser")->findBy(
          array('email' => $email
          )
        );

      	// Si el usuario no existe, se inserta en la DB.
        if (count($isset_user) == 0) {
          $em->persist($user);
          $em->flush();

          $result = $em->getRepository("BackendBundle:TblUser")->findBy(array('activo' => 1));

          $data = array(
            'status' => 'OK',
            'msg' => 'El usuario ha sido creado con éxito!',
            'users' => $result
          );
        } else {
      		// Si el usuario existe, retornamos mensaje de usuario duplicado.
          $data = array(
            'status' => 'ERROR',
            'msg' => 'El usuario ya se encuentra registrado!'
          );
        }
      };
    }

    $jsonResponse = $serializer->serialize($data, 'json');
    return new Response($jsonResponse);
  }

  public function editAction(Request $request) {
    // Cargo los servicios que voy a utilizar.
    $serializer = SerializerBuilder::create()->build();
    $params = (object) [];

    $params->id = $request->request->get("id");
    $params->username = $request->request->get("username");
    $params->password = $request->request->get("password");
    $params->nombre = $request->request->get("nombre");
    $params->apellido = $request->request->get("apellido");
    $params->email = $request->request->get("email");
    $params->role = $request->request->get("role");

    // Por default $data devuelve un error generico.
    $data = array(
      'status' => 'ERROR',
      'msg' => 'El usuario no pudo ser editado.'
      );

    if ($params->id != null) {
      // Busco la entidad correspondiente al usuario logueado.
      $em = $this->getDoctrine()->getManager();
      $user = $em->getRepository("BackendBundle:TblUser")->findOneBy(
        array(
          'id' => $params->id
        ));

        // poner los datos del usuario logueado arriba
        // crear el botón logout
        // al eliminar un usuario pasarlo a Activo = 0

      // Busco en la DB si existe un usuario con el email ingresado.
      $isset_email = $em->getRepository("BackendBundle:TblUser")->findBy(
          array(
             'email' => $params->email,
             ));

      $qb = $em->createQueryBuilder();
      $qb->select('u')
         ->from('BackendBundle:TblUser', 'u')
         ->where('u.id != '.$params->id)
         ->andWhere('u.username = :username')
         ->setParameter('username', $params->username);
      $query = $qb->getQuery();
      $result = $query->getResult();

       var_dump($result);
       die();

      // Busco en la DB si existe un usuario con el username ingresado.
      $isset_username = $em->getRepository("BackendBundle:TblUser")->findBy(
          array(
             'username' => $params->username
             ));

      if (isset($isset_email)) {

        $email = $isset_email->getEmail();
        var_dump($email);
        die();
        if ($isset_email->email == $user->email && $isset_email->id != $user->id) {
          // Crear la respuesta.. ya existe un usuario con este email.
          $data["data"] = 'Ya existe un usuario con el email ingresado.';
        }
      }

      if (isset($isset_username)) {
        if ($isset_username->username == $user->username && $isset_username->id != $user->id) {
          // Crear la respuesta.. ya existe un usuario con este username.
          $data["data"] = 'Ya existe un usuario con el username ingresado.';
        }
      }

      // Convertimos los datos del request a formato Json.
      $json = $request->get('json', null);
      $params = json_decode($json);

      // Si $json es distinto a null, procedo a crear el usuario.
      if ($json != null) {
        $username = (isset($params->username)) ? $params->username : null;
        $nombre = (isset($params->nombre)) ? $params->nombre : null;
        $apellido = (isset($params->apellido)) ? $params->apellido : null;
        $email = (isset($params->email)) ? $params->email : null;

        // Valido que ninguno de los datos recibidos sea null.
        if ($email != null && $username != null && $nombre != null && $apellido != null) {
          $user->setUsername($username);
          $user->setNombre($nombre);
          $user->setApellido($apellido);
          $user->setEmail($email);

          // Persisto los datos en la DB.
          $em->persist($user);
          $em->flush();

          $data = array(
            'status' => 'OK',
            'msg' => 'El usuario ha sido modificado con exito!'
          );
        }
      }
    }
  }
}
