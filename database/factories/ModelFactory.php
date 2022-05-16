<?php

/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Brackets\AdminAuth\Models\AdminUser::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'password' => bcrypt($faker->password),
        'remember_token' => null,
        'activated' => true,
        'forbidden' => $faker->boolean(),
        'language' => 'en',
        'deleted_at' => null,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        'last_login_at' => $faker->dateTime,
        
    ];
});/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Wallet::class, static function (Faker\Generator $faker) {
    return [
        'code' => $faker->sentence,
        'private_key' => $faker->sentence,
        'amount_usdt' => $faker->randomFloat,
        'user_id' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\EmailTemplate::class, static function (Faker\Generator $faker) {
    return [
        'code' => $faker->sentence,
        'subject' => $faker->sentence,
        'content' => $faker->text(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, static function (Faker\Generator $faker) {
    return [
        'full_name' => $faker->sentence,
        'email' => $faker->email,
        'email_verified_at' => $faker->dateTime,
        'avatar' => $faker->sentence,
        'intro' => $faker->text(),
        'remember_token' => null,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        'referral_code' => $faker->sentence,
        'password' => bcrypt($faker->password),
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Coin::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'range' => $faker->randomNumber(5),
        'min' => $faker->randomFloat,
        'max' => $faker->randomFloat,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Coin::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'image' => $faker->sentence,
        'alias' => $faker->sentence,
        'range' => $faker->randomNumber(5),
        'min' => $faker->randomFloat,
        'max' => $faker->randomFloat,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Level::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->randomNumber(5),
        'amount' => $faker->randomFloat,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Level::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->randomNumber(5),
        'amount' => $faker->randomFloat,
        'total_trade' => $faker->randomFloat,
        'commission_f1' => $faker->randomFloat,
        'master_ib' => $faker->randomFloat,
        'total_f1' => $faker->randomNumber(5),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Level::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->randomNumber(5),
        'amount' => $faker->randomFloat,
        'commission_f1' => $faker->randomFloat,
        'total_f1' => $faker->randomNumber(5),
        'total_trade' => $faker->randomFloat,
        'master_ib' => $faker->randomFloat,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\SettingRefund::class, static function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->sentence,
        'day' => $faker->randomNumber(5),
        'amount' => $faker->randomFloat,
        'percent' => $faker->randomFloat,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\SettingRefund::class, static function (Faker\Generator $faker) {
    return [
        'day' => $faker->randomNumber(5),
        'amount' => $faker->randomFloat,
        'percent' => $faker->randomFloat,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\HistoryWithdraw::class, static function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->sentence,
        'coin' => $faker->sentence,
        'amount_fee' => $faker->randomFloat,
        'amount' => $faker->randomFloat,
        'code' => $faker->sentence,
        'status' => $faker->randomNumber(5),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        'reason' => $faker->sentence,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\HistoryDeposit::class, static function (Faker\Generator $faker) {
    return [
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Faq::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'content' => $faker->text(),
        'sort' => $faker->randomNumber(5),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\PhoneCountry::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\HistoryRefund::class, static function (Faker\Generator $faker) {
    return [
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Advertisement::class, static function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'image' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Discount::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'image' => $faker->sentence,
        'date_show_image' => $faker->sentence,
        'deposit' => $faker->randomFloat,
        'discount' => $faker->randomFloat,
        'from_date' => $faker->sentence,
        'to_date' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Support::class, static function (Faker\Generator $faker) {
    return [
        'full_name' => $faker->sentence,
        'email' => $faker->email,
        'phone' => $faker->sentence,
        'content' => $faker->text(),
        'response' => $faker->text(),
        'status' => $faker->boolean(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Wheel::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'prize' => $faker->randomNumber(5),
        'sort' => $faker->randomNumber(5),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\WheelSetting::class, static function (Faker\Generator $faker) {
    return [
        'amount' => $faker->randomFloat,
        'prize' => $faker->randomNumber(5),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Advertisement::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->firstName,
        'sort' => $faker->randomNumber(5),
        'publish' => $faker->boolean(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\AutoBot::class, static function (Faker\Generator $faker) {
    return [
        'name' => $faker->text(),
        'price' => $faker->randomNumber(5),
        'commission_7' => $faker->randomFloat,
        'commission_21' => $faker->randomFloat,
        'commission_30' => $faker->randomFloat,
        'commission_90' => $faker->randomFloat,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
/** @var  \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\HistoryBot::class, static function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->sentence,
        'bot_id' => $faker->sentence,
        'time' => $faker->randomNumber(5),
        'time_expired' => $faker->sentence,
        'status' => $faker->boolean(),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        
        
    ];
});
