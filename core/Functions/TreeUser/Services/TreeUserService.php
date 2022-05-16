<?php

namespace Core\Functions\TreeUser\Services;

use Core\Repositories\Contracts\OrderInterface;

class TreeUserService
{

    private $orderRepository;

    public function __construct(OrderInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function show($user, $level = 1)
    {
        $view = '';
        $totalChildTrade = 0;
        if (!empty($user->children)) {
            $active = $level === 1 ? 'active' : '';
            $view .= '<ul class="filter__tree ' . $active . '">';
            foreach ($user->children as $child) {
                $totalChildTrade += $this->orderRepository->getTotalAmountInWeek($child->id);
                $totalTrade = $this->orderRepository->getTotalAmountUser($child->id);
                $agency = $child->level > 0 ? '<div class="email__upgrade">
                            <img class="upgrade__img" src="' . asset('frontend/images/icons/icAuthUpgrade.svg') . '" />
                        </div>' : '';
                $view .= '<li class="filter__tree-item">
                    <span class="filter__link">
                        ' . $agency . '
                        <span class="link__text email">F' . $level . ': ' . $child->email . '</span>
                        <span class="link__text">Trade : $' . number_format($totalTrade, 2) . '</span>
                        <span class="link__text">Deposit : $' . number_format($child->total_deposit, 2) . '</span>
                        <span class="link__text">Transfer : $' . number_format($child->total_transfer, 2) . '</span>
                        <span class="link__text f1">F1 : ' . count($child->children) . '</span>
                    </span>
                ';
                $level++;
                $result = $this->show($child, $level);
                $view .= $result['view'] . '</li>';
                $totalChildTrade += $result['totalChildTrade'];
                $level--;
            }
            $view .= '</ul>';
        }
        return ['view' => $view, 'totalChildTrade' => $totalChildTrade];
    }

    public function getTotalTreeTrade($user, $level = 1) {
        $totalChildTrade = 0;
        if (!empty($user->children)) {
            foreach ($user->children as $child) {
                $totalChildTrade += $this->orderRepository->getTotalAmountInWeek($child->id);
                $level++;
                $totalChildTrade += $this->getTotalTreeTrade($child, $level);
                $level--;
            }
        }
        return $totalChildTrade;
    }
}
