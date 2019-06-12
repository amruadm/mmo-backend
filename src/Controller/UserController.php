<?php

namespace App\Controller;

use App\Dto\UserCreditinals;
use App\Dto\UserInfo;
use App\Repository\UserRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class UserController extends AbstractFOSRestController
{
    /**
     * Проверка пользовательских данных.
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
     * @param UserCreditinals                  $creditinals
     * @param UserRepository                   $userRepository
     * @param UserPasswordEncoderInterface     $passwordEncoder
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @return JsonResponse
     * @ParamConverter("creditinals", converter="fos_rest.request_body")
     *
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

        $userInfo        = new UserInfo();
        $userInfo->login = $user->getLogin();
        $userInfo->id    = $user->getId();

        return $this->json($userInfo);
    }
}
