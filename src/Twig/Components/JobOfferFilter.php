<?php

namespace App\Twig\Components;

use App\Repository\JobOfferRepository;
use Symfony\Bundle\SecurityBundle\Security as SecurityBundleSecurity;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('JobOfferFilter', template: 'components/JobOfferFilter.html.twig')]
final class JobOfferFilter
{
    use DefaultActionTrait;


    #[LiveProp(writable: true, url: true)]
    public ?string $query = null;


    private SecurityBundleSecurity $security;


    public function __construct(private JobOfferRepository $jor,  SecurityBundleSecurity $security)
    {
        $this->jor = $jor;
        $this->security = $security;
    }

    public function getJobOffers(): array
    {

        if ($this->query) { // S'il y a une requête, on filtre les posts
            $result = $this->jor->findByQuery($this->query, $this->security->getUser());
            return $result;
        }
        $user = $this->security->getUser();
        $result  = $this->jor->findBy(['app_user' => $user]);
        return $result;
    }
}
