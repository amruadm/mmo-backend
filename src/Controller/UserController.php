<?php

namespace App\Controller;

use App\Dto\RegisterData;
use App\Dto\UserCreditinals;
use App\Dto\UserInfo;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class UserController extends AbstractFOSRestController
{
    /**
     * Проверка пользовательских данных.
     *
     * @param UserCreditinals                  $creditinals
     * @param UserRepository                   $userRepository
     * @param UserPasswordEncoderInterface     $passwordEncoder
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @Rest\Post("/api/v1/user/check-auth")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Найденная информация об пользователе.",
     *     @Model(type=UserInfo::class)
     * )
     *
     * @SWG\Parameter(
     *     name="JSON Body",
     *     in="body",
     *     description="Информация об пользователе.",
     *     @Model(type=UserCreditinals::class)
     * )
     *
     * @ParamConverter("creditinals", converter="fos_rest.request_body")
     *
     * @return JsonResponse
     */
    public function checkAuth(
        UserCreditinals $creditinals,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        ConstraintViolationListInterface $validationErrors
    ): JsonResponse {
        if ($validationErrors->count() > 0) {
            throw new BadRequestHttpException($validationErrors->get(0)->getMessage());
        }

        $user = $userRepository->findOneBy(['login' => $creditinals->login]);
        if (null === $user) {
            throw new NotFoundHttpException('Пользователь с таким логином не найден');
        }

        if (false === $passwordEncoder->isPasswordValid($user, $creditinals->password)) {
            throw new NotFoundHttpException('Неверный пароль');
        }

        return $this->json($user->toInfo());
    }

    /**
     * Регистрация пользователя.
     *
     * @param RegisterData                     $registerData
     * @param EntityManagerInterface           $entityManager
     * @param UserPasswordEncoderInterface     $passwordEncoder
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @Rest\Post("/api/v1/user/register/")
     *
     * @SWG\Response(
     *     response=201,
     *     description="Информация об созданом пользователе",
     *     @Model(type=UserInfo::class)
     * )
     *
     * @SWG\Parameter(
     *     name="JSON Body",
     *     in="body",
     *     description="Информация регистрируемого пользователя",
     *     @Model(type=RegisterData::class)
     * )
     *
     * @ParamConverter("registerData", converter="fos_rest.request_body")
     *
     * @return JsonResponse
     */
    public function register(
        RegisterData $registerData,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        ConstraintViolationListInterface $validationErrors
    ): JsonResponse {
        if ($validationErrors->count() > 0) {
            throw new BadRequestHttpException($validationErrors->get(0)->getMessage());
        }

        $exists = $entityManager->getRepository(User::class)->findOneBy(['login' => $registerData->login]);
        if (null !== $exists) {
            throw new BadRequestHttpException('Пользователь с таким именем уже существует');
        }

        $user = new User();
        $user->setLogin($registerData->login);
        $user->setEmail($registerData->email);
        $user->setPassword($passwordEncoder->encodePassword($user, $registerData->password));

        $entityManager->persist($user);

        $entityManager->flush();

        return $this->json($user->toInfo(), Response::HTTP_CREATED);
    }

    /**
     * Проверка имени пользователя на существование.
     *
     * @param User                   $user
     *
     * @Rest\Post("/api/v1/user/check-username/{login}")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Наличие пользователя (код 200 если существует)"
     * )
     *
     * @SWG\Parameter(
     *     name="login",
     *     in="path",
     *     description="Имя пользователя",
     *     type="string"
     * )
     *
     * @return JsonResponse
     */
    public function checkUsername(
        User $user
    ): JsonResponse {
        return $this->json((null !== $user) ? true : false);
    }
}
