<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Security\Voter\UserVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserDeleteController extends UserAbstractController
{
    #[ParamConverter('user', options: ['mapping' => ['user_id' => 'id']])]
    public function delete(User $user, Request $request): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::DELETE, $user);

        $this->validateCsrf('user_delete', $request->request->get('_token'));

        $this->manager->delete($user);

        return $this->redirectToRefererOrHome($request);
    }
}
