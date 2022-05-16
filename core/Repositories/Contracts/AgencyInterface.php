<?php

namespace Core\Repositories\Contracts;

interface AgencyInterface
{
    public function findUser($id);

    public function findWalletGame($userId, $type);

    public function isBuy($userId);

    public function findParentReferralCode($referralCode);

    public function findChildReferralCode($code);

    public function findLevel($total_f1);

}
