<?php

return [
    'email' => [
        'verify_account' => '001',
        'withdraw_temp' => '002',
        'internal_withdraw' => '003',
        'withdraw_success' => '004',
        'deposit_success' => '005',
        'supported' => '006',
    ],
    'telegram' => [
        'withdraw_request' => 'tele_001',
        'kyc_request' => 'tele_002'
    ],
    'publish' => [
        'yes' => 1,
        'no' => 2,
    ],
    'main_wallet' => 'main',
    'discount_wallet' => 'discount',
    'trial_wallet' => 'trial',
    'orders' => [
        'red' => 0,
        'green' => 1,
        'yellow' => 2
    ],
    'PAGINATE_50' => 50,
    'PAGINATE_10' => 10,
    'path_user' => 'uploads/profile/',
    'history_agency' => 'Buy agency',
    'history_commission_agency' => 'Affiliate Marketing: Received ${AMOUNT} from {USER}',
    'history_commission_daily' => 'Commission: Received ${AMOUNT} from F{NAME}',
    'history_commission_from_child' => 'Commission: Received ${AMOUNT} from {User}',
    'history_commission_master_ib' => 'Master IB: Upgrade level {NAME}',
    'history_refund' => 'Refund: Received ${AMOUNT}',
    'history_internal_transfer' => [
        'to' => 'Transfer Min ${AMOUNT} to address MIN{CODE}',
        'from' => 'Received Min ${AMOUNT} from address MIN{CODE}',
    ],
    'history_cron_deposit' => 'Deposit to address {CODE} successfully {AMOUNT} - {NETWORK}',
    'history_withdraw' => [
        'pending' => 'Withdraw ${AMOUNT} success',
        'reject' => 'Plus ${AMOUNT} due to refusal of withdrawals',
    ],
    'history_plus_create_account_trial' => 'Plus ${AMOUNT} create account',
    'history_order' => 'Trade {COIN}: ${AMOUNT} at {DATE}',
    'history_result_order' => 'Plus ${AMOUNT} from trade {COIN}',
    'history_refill' => 'Plus ${AMOUNT} from wallet refill',
    'history_repay' => 'Plus ${AMOUNT} from error trade {COIN} at {DATE}',
    'history_discount' => 'Plus ${AMOUNT} from {NAME} promotion',
    'history_lucky_wheel' => 'Plus ${AMOUNT} from Lucky Wheel',
    'history_buy_bot' => 'Buy package bot {NAME}',
    'history_commission_bot' => 'Commission Bot: Received ${AMOUNT} from {USER}, package bot {NAME}',
    'history_commission_bot_daily' => 'BOT TRADE: Received ${AMOUNT}',
    'history_commission_bot_daily_parent' => 'Commission BOT Child: Received ${AMOUNT} from {USER}',
    'history_commission_bot_unlock' => 'BOT TRADE: Unlock bot received ${AMOUNT}',
    'statusApi' => [
        'success' => 1,
        'failed' => 2
    ],
    'gasLimit' => 40000,
    'gasPrice' => 50000000000,
    'walletCate' => [
        'eth' => 1,
        'btc' => 2
    ],
    'eth' => 'eth',
    'usdt' => 'usdt',
    'au' => 'au',

    'token_withdraw' => 1,
    'token_send' => 2,
    'token_failed' => 0,
    'token_success' => 1,
    'token_pending' => 2,

    'amount_plus_trial' => 10000,
    'fee_withdraw' => 3,
    'minutes_limit' => 30,
    'min_withdraw' => 50,
    'min_transfer' => 50,

    // Verify
    'not_verify_user' => 0,
    'not_verify_user_text' => 'Not verify',
    'pending_verify_user' => 1,
    'pending_verify_user_text' => 'Pending verify',
    'verify_user' => 2,
    'verify_user_text' => 'Verified',
    // Orders Text
    'low' => 'Sell',
    'higher' => 'Buy',
    'balance' => 'Balance x7',
    // TODO Define type cho từng history trong này nha
    'type_history' => [
        'internal_transfer' => 1,
        'commission_agency' => 2,
        'commission_daily' => 3,
        'refund' => 4,
        'cron_deposit' => 5,
        'create_account_trial' => 6,
        'withdraw_pending' => 7,
        'withdraw_reject' => 8,
        'commission_agency_parent' => 9,
        'commission_level' => 10,
        'commission_from_child' => 11,
        'order' => 12,
        'result_order' => 13,
        'refill' => 14,
        'repay' => 15,
        'discount' => 16,
        'lucky_wheel' => 17,
        'buy_bot' => 18,
        'commission_bot' => 19,
        'commission_bot_daily' => 20,
        'commission_bot_daily_parent' => 21,
        'commission_bot_unlock' => 22,
    ],
    'telegram_message' => [
        'deposit' => 'User {USER} has deposit successful {AMOUNT} USDT to the address {ADDRESS}',
        'transfer' => 'User {USER_FROM} has transfer successful ${AMOUNT} to user {USER_TO}',
        'withdraw_request' => 'User {USER} has request withdraw ${AMOUNT}',
        'kyc_request' => 'User {USER} has request verify account',
        'support' => 'User {USER} has request support {CONTENT}'
    ],

    'status_withdraw' => [
        'temp' => 1, // mới tạo
        'pending' => 2, // user xác nhận mail
        'pending_text' => 'Pending', // user xác nhận mail
        'approved' => 3, // admin xác nhận mail
        'approved_text' => 'Approved', // admin xác nhận mail
        'reject' => 4, // Reject
        'reject_text' => 'Reject',
        'expired' => 5, // Hết hạn
    ],

    'reason_expired_withdraw' => 'Expired withdraw',
    'cache' => [
        'order_in_week' => 'order_week:',
        'order_all' => 'order_all:'
    ],

    'abi' => '[
      {
        "constant": true,
        "inputs": [],
        "name": "name",
        "outputs": [{ "name": "", "type": "string" }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
      },
      {
        "constant": false,
        "inputs": [
          { "name": "_spender", "type": "address" },
          { "name": "_value", "type": "uint256" }
        ],
        "name": "approve",
        "outputs": [{ "name": "", "type": "bool" }],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "function"
      },
      {
        "constant": true,
        "inputs": [],
        "name": "totalSupply",
        "outputs": [{ "name": "", "type": "uint256" }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
      },
      {
        "constant": false,
        "inputs": [
          { "name": "_from", "type": "address" },
          { "name": "_to", "type": "address" },
          { "name": "_value", "type": "uint256" }
        ],
        "name": "transferFrom",
        "outputs": [{ "name": "", "type": "bool" }],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "function"
      },
      {
        "constant": true,
        "inputs": [],
        "name": "decimals",
        "outputs": [{ "name": "", "type": "uint8" }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
      },
      {
        "constant": true,
        "inputs": [{ "name": "_owner", "type": "address" }],
        "name": "balanceOf",
        "outputs": [{ "name": "balance", "type": "uint256" }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
      },
      {
        "constant": true,
        "inputs": [],
        "name": "symbol",
        "outputs": [{ "name": "", "type": "string" }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
      },
      {
        "constant": false,
        "inputs": [
          { "name": "_to", "type": "address" },
          { "name": "_value", "type": "uint256" }
        ],
        "name": "transfer",
        "outputs": [{ "name": "", "type": "bool" }],
        "payable": false,
        "stateMutability": "nonpayable",
        "type": "function"
      },
      {
        "constant": true,
        "inputs": [
          { "name": "_owner", "type": "address" },
          { "name": "_spender", "type": "address" }
        ],
        "name": "allowance",
        "outputs": [{ "name": "", "type": "uint256" }],
        "payable": false,
        "stateMutability": "view",
        "type": "function"
      },
      { "payable": true, "stateMutability": "payable", "type": "fallback" },
      {
        "anonymous": false,
        "inputs": [
          { "indexed": true, "name": "owner", "type": "address" },
          { "indexed": true, "name": "spender", "type": "address" },
          { "indexed": false, "name": "value", "type": "uint256" }
        ],
        "name": "Approval",
        "type": "event"
      },
      {
        "anonymous": false,
        "inputs": [
          { "indexed": true, "name": "from", "type": "address" },
          { "indexed": true, "name": "to", "type": "address" },
          { "indexed": false, "name": "value", "type": "uint256" }
        ],
        "name": "Transfer",
        "type": "event"
      }
    ]'
];
