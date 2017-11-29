<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\User;
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
    $users = $em->getRepository("BackendBundle:User")->findAll();

    $data = array(
      'status' => 'OK',
      'users' => $users
    );

    $jsonResponse = $serializer->serialize($data, 'json');
    return new Response($jsonResponse);
  }

  public function newAction(Request $request) {
      // Cargo los servicios que voy a utilizar.
    $serializer = SerializerBuilder::create()->build();

    $params = (object) [];
    // Recibo los parametros del request.
    $params->email = $request->request->get("email");
    $params->password = $request->request->get("password");
    $params->username = $request->request->get("username");
    $params->role = $request->request->get("role");

    // Por default $data devuelve un error generico.
    $data = array(
      'status' => 'ERROR',
      'msg' => 'El usuario no pudo ser creado.'
    );

    // Chequeo si alguno de los datos recibidos es null, si es así, retorno un mensaje de error.
    if ($params->email == NULL OR $params->password == NULL OR $params->username == NULL OR $params->role == NULL) {
      $jsonResponse = $serializer->serialize($data, 'json');
      return new Response($jsonResponse);
    }

    // Valido que el Email sea un realmente un email mediante Asserts.
    $emailContraint = new Assert\Email();
    $emailContraint->message = "Este email no es válido";
    $validate_email = $this->get('validator')->validate($params->email, $emailContraint);

    // Chequeo que el email ingresado tenga el formato correcto.
    if (count($validate_email) != 0) {
      $data['msg'] = 'El email ingresado no tiene el formato correcto.';
      $jsonResponse = $serializer->serialize($data, 'json');
      return new Response($jsonResponse);
    }

    $params->role = [''.$params->role.''];

	  // Instanciamos un objeto User y seteamos sus datos.
    $user = new User();
    $user->setUsername($params->username);
    $user->setUsernameCanonical($params->username);
    $user->setEmail($params->email);
    $user->setEmailCanonical($params->email);
    $user->setRoles($params->role);
    $user->setEnabled(true);

  	 // Agregamos una palabra clave encriptada en nuestra contraseña, que será parte del Hash.
    $psw = crypt($params->password, 'ratsoft');
    $user->setPassword($psw);

    // Busco en la DB si existe un usuario con el username envíado, donde el Id sea distinto al usuario a editar.
    $em = $this->getDoctrine()->getManager();
    $qb = $em->createQueryBuilder();
    $qb->select('u')
       ->from('BackendBundle:User', 'u')
       ->where('u.username = :username OR u.email = :email')
       ->setParameters(array('username' => $params->username, 'email' => $params->email));
    $query = $qb->getQuery();
    $result = $query->getResult();

    // Si $result tiene un valor, es porque existe un usuario con los datos ingresados.
    if (!empty($result)) {
      $data["msg"] = 'Ya existe un usuario con el username o email ingresado.';

      $jsonResponse = $serializer->serialize($data, 'json');
      return new Response($jsonResponse);
    }

    // Persisto el nuevo usuario en la DB.
    $em->persist($user);
    $em->flush();

    // Vuelvo a traer todos los usuarios de la DB.
    $result = $em->getRepository("BackendBundle:User")->findBy(array('enabled' => true));

    // Retorno casuistica positiva.
    $data = array(
      'status' => 'OK',
      'msg' => 'El usuario ha sido creado con éxito!',
      'users' => $result
    );

    $jsonResponse = $serializer->serialize($data, 'json');
    return new Response($jsonResponse);
  }

  public function editAction(Request $request) {
    // Cargo los servicios que voy a utilizar.
    $serializer = SerializerBuilder::create()->build();
    $params = (object) [];

    $params->id = $request->request->get("id");
    $params->role = $request->request->get("role");
    $params->email = $request->request->get("email");
    $params->username = $request->request->get("username");
    $params->password = $request->request->get("password");

    if ($params->role == 'ROLE_ADMIN') {
      // $params->role = 'a:1:{i:0;s:10:"ROLE_ADMIN";}';
      $params->role = ['ROLE_ADMIN'];
    } else {
      // $params->role = 'a:0:{}';
      $params->role = [];
    }

    // Por default $data devuelve un error generico.
    $data = array(
      'status' => 'ERROR',
      'msg' => 'Ocurrió un error al editar el usuario.'
    );

    if ($params->id != null) {
      // Busco la entidad correspondiente al usuario a editar.
      $em = $this->getDoctrine()->getManager();
      $user = $em->getRepository("BackendBundle:User")->findOneBy(
        array(
          'id' => $params->id
        )
      );

      // Busco en la DB si existe un usuario con el username envíado, donde el Id sea distinto al usuario a editar.
      $qb = $em->createQueryBuilder();
      $qb->select('u')
         ->from('BackendBundle:User', 'u')
         ->where('u.id != :id')
         ->andWhere('u.username = :username OR u.email = :email')
         ->setParameters(array('id' => $params->id, 'username' => $params->username, 'email' => $params->email));
      $query = $qb->getQuery();
      $result = $query->getResult();

      // Si $result tiene un valor, es porque existe un usuario con los datos ingresados.
      if (!empty($result)) {
        $data["msg"] = 'Ya existe un usuario con el username o email ingresado.';

        $jsonResponse = $serializer->serialize($data, 'json');
        return new Response($jsonResponse);
      }

      // Valido que email y username no sean null
      if ($params->email != null && $params->username != null) {
        $user->setUsername($params->username);
        $user->setUsernameCanonical($params->username);
        $user->setEmail($params->email);
        $user->setEmailCanonical($params->email);
        $user->setRoles($params->role);
        // $user->setEnabled($enabled); // Faltaría agregar la baja de usuarios.

        // Persisto los datos en la DB.
        $em->persist($user);
        $em->flush();

        // Devuelve todos los usuarios activos para refrescar la grilla.
        $result = $em->getRepository("BackendBundle:User")->findBy(array('enabled' => true));

        $data = array(
          'status' => 'OK',
          'msg' => 'El usuario ha sido modificado con exito!',
          'users' => $result
          );
      }

      $jsonResponse = $serializer->serialize($data, 'json');
      return new Response($jsonResponse);
    }
  }

  public function deleteAction(Request $request) {
    // Cargo los servicios que voy a utilizar.
    $serializer = SerializerBuilder::create()->build();
    $params = (object) [];

    $params->id = $request->request->get("id");

    // Por default $data devuelve un error generico.
    $data = array(
      'status' => 'ERROR',
      'msg' => 'El usuario no pudo ser eliminado.'
      );

    if ($params->id != null) {
      // Busco la entidad correspondiente al usuario logueado.
      $em = $this->getDoctrine()->getManager();
      $user = $em->getRepository("BackendBundle:User")->findOneBy(
        array(
          'id' => $params->id
        )
      );

      // Valido que el usuario exista en la DB.
      if (!empty($user)) {
        // Seteo el estado Enabled en false.
        $user->setEnabled(false);

        // Persisto los datos en la DB.
        $em->persist($user);
        $em->flush();

        // Devuelve todos los usuarios activos para refrescar la grilla.
        $result = $em->getRepository("BackendBundle:User")->findBy(array('enabled' => true));

        $data = array(
          'status' => 'OK',
          'msg' => 'El usuario ha sido eliminado con exito!',
          'users' => $result
        );
      }
    }

    $jsonResponse = $serializer->serialize($data, 'json');
    return new Response($jsonResponse);
  }
}
