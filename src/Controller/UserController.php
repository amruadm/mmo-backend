<?php

namespace App\Controller;

use App\Dto\UserCreditinals;
use App\Dto\UserInfo;
use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/api/v1/user/check-auth", name="user", methods={"POST"})
     *
     * @param Request                      $request
     * @param UserRepository               $userRepository
     * @param SerializerInterface          $serializer
     * @param ValidatorInterface           $validator
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function checkAuth(
        Request $request,
        UserRepository $userRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        if ($request->getContentType() !== 'application/json') {
            throw new BadRequestHttpException();
        }

        /** @var UserCreditinals $creditinals */
        $creditinals = $serializer->deserialize($request->getContent(), UserCreditinals::class, 'json');

        if (false === $validator->validate($creditinals)) {
            throw new BadRequestHttpException();
        }

        $user = $userRepository->findOneBy(['login' => $creditinals->login]);
        if (null === $user) {
            throw new NotFoundHttpException();
        }

        $encodedPassword = $passwordEncoder->encodePassword($user, $creditinals->password);

        if ($user->getPassword() !== $encodedPassword) {
            throw new NotFoundHttpException();
        }

        $userInfo = new UserInfo();
        $userInfo->login = $user->getLogin();
        $userInfo->id = $user->getId();

        return $this->json($userInfo);
    }
}
