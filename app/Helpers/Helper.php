<?php

use App\Models\Currency;
use App\Models\Exam;
use App\Models\FileHandler;
use App\Models\MultiLanguage;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;

if (!function_exists("getSetting")) {
    function getSetting($option, $default = NULL)
    {
        $system_settings = config('settings');

        if ($option && isset($system_settings[$option])) {
            return $system_settings[$option];
        } else {
            return $default;
        }
    }
}

function replaceKeywordForTemplate($content, $customizedFieldsArray)
{
    $pattern = '/{{(.*?)}}/';
    $content = preg_replace_callback($pattern, function ($matches) use ($customizedFieldsArray) {
        $field = trim($matches[1]);
        if (array_key_exists($field, $customizedFieldsArray)) {
            return $customizedFieldsArray[$field];
        }
        return $matches[0];
    }, $content);
    return $content;
}


function settingImageStoreUpdate($option_value, $requestFile)
{

    if ($requestFile) {

        /*File Manager Call upload*/
        if ($option_value && $option_value != null) {
            $new_file = FileManager::where('id', $option_value)->first();

            if ($new_file) {
                $new_file->removeFile();
                $uploaded = $new_file->upload('Setting', $requestFile, '', $new_file->id);
            } else {
                $new_file = new FileManager();
                $uploaded = $new_file->upload('Setting', $requestFile);
            }
        } else {
            $new_file = new FileManager();
            $uploaded = $new_file->upload('Setting', $requestFile);
        }

        /*End*/

        return $uploaded->id;
    }

    return null;
}


if (!function_exists("getDefaultImage")) {
    function getDefaultImage()
    {
        return asset('assets/images/no-image.jpg');
    }
}

if (!function_exists("activeIfMatch")) {
    function activeIfMatch($path)
    {
        if (auth::user()->is_admin()) {
            return Request::is($path . '*') ? 'mm-active' : '';
        } else {
            return Request::is($path . '*') ? 'active' : '';
        }
    }
}

if (!function_exists("activeIfFullMatch")) {
    function activeIfFullMatch($path)
    {
        if (auth::user()->is_admin()) {
            return Request::is($path) ? 'mm-active' : '';
        } else {
            return Request::is($path) ? 'active' : '';
        }
    }
}

if (!function_exists("openIfFullMatch")) {
    function openIfFullMatch($path)
    {
        return Request::is($path) ? 'has-open' : '';
    }
}


if (!function_exists("toastMessage")) {
    function toastMessage($message_type, $message)
    {
        Toastr::$message_type($message, '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-top-right']);
    }
}

if (!function_exists("getDefaultLanguage")) {
    function getDefaultLanguage()
    {
        $language = MultiLanguage::where('default', STATUS_ACTIVE)->first();
        if ($language) {
            $iso_code = $language->iso_code;
            return $iso_code;
        }

        return 'en';
    }
}

if (!function_exists("getCurrencySymbol")) {
    function getCurrencySymbol()
    {
        $currency = Currency::where('default_currency', STATUS_ACTIVE)->first();
        if ($currency) {
            $symbol = $currency->symbol;
            return $symbol;
        }

        return '';
    }
}

if (!function_exists("getIsoCode")) {
    function getIsoCode()
    {
        $currency = Currency::where('current_currency', STATUS_ACTIVE)->first();
        if ($currency) {
            $currency_code = $currency->currency_code;
            return $currency_code;
        }
        return '';
    }
}

if (!function_exists("getCurrencyPlacement")) {
    function getCurrencyPlacement()
    {
        $currency = Currency::where('current_currency', STATUS_ACTIVE)->first();
        $placement = 'before';
        if ($currency) {
            $placement = $currency->currency_placement;
            return $placement;
        }

        return $placement;
    }
}

if (!function_exists("showCurrency")) {
    function showCurrency($price)
    {
        $price = getNumberFormat($price);
        if (config('app.currencyPlacement') == 'after') {
            return $price . config('app.currencySymbol');
        } else {
            return config('app.currencySymbol') . $price;
        }
    }
}


if (!function_exists("getNumberFormat")) {
    function getNumberFormat($amount)
    {
        return number_format($amount, 2, '.', '');
    }
}

if (!function_exists("decimalToInt")) {
    function decimalToInt($amount)
    {
        return number_format(number_format($amount, 2, '.', '') * 100, 0, '.', '');
    }
}

if (!function_exists("intToDecimal")) {
}
function intToDecimal($amount)
{
    return number_format($amount / 100, 2, '.', '');
}

if (!function_exists("appLanguages")) {
    function appLanguages()
    {
        return MultiLanguage::where('status', 1)->get();
    }
}

if (!function_exists("selectedLanguage")) {
    function selectedLanguage()
    {

        $language = MultiLanguage::where('iso_code', session()->get('local'))->first();

        if (!$language) {
            $language = MultiLanguage::find(1);
            if ($language) {
                $ln = $language->iso_code;
                session(['local' => $ln]);
                App::setLocale(session()->get('local'));
            }
        }

        return $language;
    }
}

if (!function_exists("getVideoFile")) {
    function getFile($file)
    {
        if ($file == '' || $file == null) {
            return null;
        }

        try {
            if (env('STORAGE_DRIVER') == "s3") {
                if (Storage::disk('s3')->exists($file)) {
                    $s3 = Storage::disk('s3');
                    return $s3->url($file);
                }
            }
        } catch (Exception $e) {
        }

        return asset($file);
    }
}

if (!function_exists('getSlug')) {
    function getSlug($text)
    {
        if ($text) {
            $data = preg_replace("/[~`{}.'\"\!\@\#\$\%\^\&\*\(\)\_\=\+\/\?\>\<\,\[\]\:\;\|\\\]/", "", $text);
            $slug = preg_replace("/[\/_|+ -]+/", "-", $data);
            return $slug;
        }
        return '';
    }
}


if (!function_exists('getCustomerCurrentBuildVersion')) {
    function getCustomerCurrentBuildVersion()
    {
        $buildVersion = getSetting('app_version');

        if (is_null($buildVersion)) {
            return 1;
        }

        return (int)$buildVersion;
    }
}

if (!function_exists('setCustomerBuildVersion')) {
    function setCustomerBuildVersion($version)
    {
        $option = Setting::firstOrCreate(['option_key' => 'app_version']);
        $option->option_value = $version;
        $option->save();
    }
}

if (!function_exists('setCustomerCurrentVersion')) {
    function setCustomerCurrentVersion()
    {
        $option = Setting::firstOrCreate(['option_key' => 'current_version']);
        $option->option_value = config('app.current_version');
        $option->save();
    }
}

if (!function_exists('getDomainName')) {
    function getDomainName($url)
    {
        $parseUrl = parse_url(trim($url));
        if (isset($parseUrl['host'])) {
            $host = $parseUrl['host'];
        } else {
            $path = explode('/', $parseUrl['path']);
            $host = $path[0];
        }
        return trim($host);
    }
}

if (!function_exists('updateEnv')) {
    function updateEnv($values)
    {
        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                setEnvironmentValue($envKey, $envValue);
            }
            return true;
        }
    }
}

if (!function_exists('setEnvironmentValue')) {
    function setEnvironmentValue($envKey, $envValue)
    {
        try {
            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);
            $str .= "\n"; // In case the searched variable is in the last line without \n
            $keyPosition = strpos($str, "{$envKey}=");
            if ($keyPosition) {
                if (PHP_OS_FAMILY === 'Windows') {
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                } else {
                    $endOfLinePosition = strpos($str, PHP_EOL, $keyPosition);
                }
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                $envValue = str_replace(chr(92), "\\\\", $envValue);
                $envValue = str_replace('"', '\"', $envValue);
                $newLine = "{$envKey}=\"{$envValue}\"";
                if ($oldLine != $newLine) {
                    $str = str_replace($oldLine, $newLine, $str);
                    $str = substr($str, 0, -1);
                    $fp = fopen($envFile, 'w');
                    fwrite($fp, $str);
                    fclose($fp);
                }
            } else if (strtoupper($envKey) == $envKey) {
                $envValue = str_replace(chr(92), "\\\\", $envValue);
                $envValue = str_replace('"', '\"', $envValue);
                $newLine = "{$envKey}=\"{$envValue}\"\n";
                $str .= $newLine;
                $str = substr($str, 0, -1);
                $fp = fopen($envFile, 'w');
                fwrite($fp, $str);
                fclose($fp);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('base64urlEncode')) {
    function base64urlEncode($str)
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }
}

if (!function_exists('getTimeZone')) {
    function getTimeZone()
    {
        return DateTimeZone::listIdentifiers(
            DateTimeZone::ALL
        );
    }
}

if (!function_exists('getErrorMessage')) {
    function getErrorMessage($e, $customMsg = null)
    {
        if ($customMsg != null) {
            return $customMsg;
        }
        if (env('APP_DEBUG')) {
            return $e->getMessage() . $e->getLine();
        } else {
            return MSG_SOMETHING_WENT_WRONG;
        }
    }
}

if (!function_exists('getFileLink')) {
    function getFileLink($id = null, $demo_image_type = null, $demo_image_dimension = null)
    {
        $file = FileHandler::select('path', 'storage_type')->find($id);
        if (!is_null($file)) {
            if (Storage::disk($file->storage_type)->exists($file->path)) {
                if ($file->storage_type == 'public' || $file->storage_type == 'local') {
                    return asset('storage/' . $file->path);
                }
                if ($file->storage_type == 'wasabi') {
                    return Storage::disk('wasabi')->url($file->path);
                }
                return Storage::disk($file->storage_type)->url($file->path);
            }
        }

        $demoImgSize = '';
        if ($demo_image_dimension != null) {
            $demoImgSize = '-d-' . $demo_image_dimension;
        }

        $demoImg = asset('assets/common/images/demo-image' . $demoImgSize . '.jpg');
        if ($demo_image_type == 'user') {
            $demoImg = asset('assets/common/images/demo-user-image' . $demoImgSize . '.jpg');
        }
        return $demoImg;
    }
}


if (!function_exists('languageLocale')) {
    function languageLocale($locale)
    {
        $data = MultiLanguage::where('code', $locale)->first();
        if ($data) {
            return $data->code;
        }
        return 'en';
    }
}


if (!function_exists('getUseCase')) {
    function getUseCase($useCase = [])
    {
        if (in_array("-1", $useCase)) {
            return __("All");
        }
        return count($useCase);
    }
}

function currentCurrency($attribute = '')
{
    $currentCurrency = Currency::where('current_currency', 1)->first();
    if (isset($currentCurrency->{$attribute})) {
        return $currentCurrency->{$attribute};
    }
    return '';
}

function getPairInfo($base_coin_id, $trade_coin_id, $property)
{
    $base_coin = Coin::where('id', $base_coin_id)->first();
    $trade_coin = Coin::where('id', $trade_coin_id)->first();


    if ($property == 'pare_name') {
        return $trade_coin->full_name . '/' . $base_coin->full_name;
    }
    if ($property == 'base_coin_name') {
        return $base_coin->full_name;
    }

    if ($property == 'trade_coin_name') {
        return $trade_coin->full_name;
    }

    if ($property == 'base_coin_price') {
        return convertCurrency(1, $base_coin->coin_type, $trade_coin->coin_type)['price'];
    }

    if ($property == 'trade_coin_price') {
    }
}

function currentCurrencyType()
{
    $currentCurrency = Currency::where('current_currency', 1)->first();
    return $currentCurrency->currency_code;
}

function currentCurrencyIcon()
{
    $currentCurrency = Currency::where('current_currency', 1)->first();
    return $currentCurrency->symbol;
}

function totalBlance()
{

    $userWallet = UserWallet::leftJoin('coins', 'user_wallets.coin_id', '=', 'coins.id')
        ->where('user_wallets.user_id', auth()->id())
        ->select([
            'user_wallets.id as wallet_id',
            'user_wallets.user_id',
            'user_wallets.balance',
            'user_wallets.balance_referral',
            'user_wallets.address',
            'coins.*'
        ])
        ->get();

    $order = 0;
    $blance = 0;

    foreach ($userWallet as $wallet) {
        $blance += convertCurrency($wallet->balance, currentCurrencyType(), $wallet->coin_type)["total"];
    }


    $blance = $blance + $order;

    return $blance;
}

function userWalletById($id = '')
{

    $userWallet = UserWallet::leftJoin('coins', 'user_wallets.coin_id', '=', 'coins.id')
        ->where('user_wallets.id', $id)
        ->select([
            'user_wallets.id as wallet_id',
            'user_wallets.user_id',
            'user_wallets.balance',
            'user_wallets.balance_referral',
            'user_wallets.address',
            'coins.*'
        ])
        ->get();

    return $userWallet;
}


// Convert currency
function convertCurrency($amount, $to = 'USD', $from = 'USD')
{
    //1-BTC-GBP
    try {
        $jsondata = "";

        $coinPriceInCurrency = Setting::where('option_key', 'COIN_PRICE_IN_CURRENCY_FOR' . $from)->first();


        if ($coinPriceInCurrency != null) {

            if ($coinPriceInCurrency->option_value == null) {
                $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
                $json = file_get_contents($url); //,FALSE,$ctx);
                $jsondata = json_decode($json, TRUE);

                $coinPriceInCurrency->option_value = $jsondata[$to];
                $coinPriceInCurrency->save();
            }

            $dateTime = Carbon::now()->addMinute(5);
            $currentTime = $dateTime->format('Y-m-d H:i:s');


            if (($coinPriceInCurrency->option_value != null) && (date('Y-m-d H:i:s', strtotime($coinPriceInCurrency->updated_at)) < $currentTime)) {
                $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
                $json = file_get_contents($url); //,FALSE,$ctx);
                $jsondata = json_decode($json, TRUE);

                $coinPriceInCurrency->option_value = $jsondata[$to];
                $coinPriceInCurrency->save();
            }
        } else {

            $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
            $json = file_get_contents($url); //,FALSE,$ctx);
            $jsondata = json_decode($json, TRUE);

            if ($jsondata != null) {
                $newObj = new Setting();
                $newObj->option_key = 'COIN_PRICE_IN_CURRENCY_FOR' . $from;
                $newObj->option_value = $jsondata[$to];
                $newObj->save();
            }
        }


        return [
            'total' => $amount * getSetting('COIN_PRICE_IN_CURRENCY_FOR' . $from),
            'price' => getSetting('COIN_PRICE_IN_CURRENCY_FOR' . $from)
        ];
    } catch (\Exception $e) {
        return [
            'total' => 0.00000000,
            'price' => 0.00000000
        ];
    }
}


function convertCurrencySwap($amount, $to = 'USD', $from = 'USD')
{
    try {
        $jsondata = "";

        $coinPriceInCurrency = Setting::where('option_key', 'COIN_PRICE_IN_CURRENCY_FOR' . $from)->first();
        if ($coinPriceInCurrency != null) {

            if ($coinPriceInCurrency->option_value == null) {
                $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
                $json = file_get_contents($url); //,FALSE,$ctx);
                $jsondata = json_decode($json, TRUE);

                $coinPriceInCurrency->option_value = $jsondata[$to];
                $coinPriceInCurrency->save();
            }

            $dateTime = Carbon::now()->addMinute(5);
            $currentTime = $dateTime->format('Y-m-d H:i:s');

            if (($coinPriceInCurrency->option_value != null) && (date('Y-m-d H:i:s', strtotime($coinPriceInCurrency->updated_at)) < $currentTime)) {
                $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
                $json = file_get_contents($url); //,FALSE,$ctx);
                $jsondata = json_decode($json, TRUE);

                $coinPriceInCurrency->option_value = $jsondata[$to];
                $coinPriceInCurrency->save();
            }
        } else {

            $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
            $json = file_get_contents($url); //,FALSE,$ctx);
            $jsondata = json_decode($json, TRUE);

            if ($jsondata != null) {
                $newObj = new Setting();
                $newObj->option_key = 'COIN_PRICE_IN_CURRENCY_FOR' . $from;
                $newObj->option_value = $jsondata[$to];
                $newObj->save();
            }
        }

        return [
            'total' => $amount * getSetting('COIN_PRICE_IN_CURRENCY_FOR' . $from),
            'price' => getSetting('COIN_PRICE_IN_CURRENCY_FOR' . $from)
        ];
    } catch (\Exception $e) {
        return [
            'total' => 0.00000000,
            'price' => 0.00000000
        ];
    }
}

function random_strings($length_of_string)
{
    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($str_result), 0, $length_of_string);
}

function broadcastPrivate($eventName, $broadcastData, $userId)
{
    //    $channelName = 'private-'.env("PUSHER_PRIVATE_CHANEL_NAME").'.' . customEncrypt($userId);
    //    dispatch(new BroadcastJob($channelName, $eventName, $broadcastData))->onQueue('broadcast-data');
}

function getUserId()
{
    try {
        return Auth::id();
    } catch (\Exception $e) {
        return 0;
    }
}


if (!function_exists('visual_number_format')) {
    function visual_number_format($value)
    {
        if (is_integer($value)) {
            return number_format($value, 2, '.', '');
        } elseif (is_string($value)) {
            $value = floatval($value);
        }
        $number = explode('.', number_format($value, 10, '.', ''));
        $intVal = (int)$value;
        if ($value > $intVal || $value < 0) {
            $intPart = $number[0];
            $floatPart = substr($number[1], 0, 8);
            $floatPart = rtrim($floatPart, '0');
            if (strlen($floatPart) < 2) {
                $floatPart = substr($number[1], 0, 2);
            }
            return $intPart . '.' . $floatPart;
        }
        return $number[0] . '.' . substr($number[1], 0, 2);
    }
}

function getError($e)
{
    if (env('APP_DEBUG')) {
        return " => " . $e->getMessage();
    }
    return '';
}

function setNotification($title, $details, $receiver_id = null, $external_link = null)
{
    try {
        $obj = new Notification();
        $obj->title = $title;
        $obj->details = $details;
        $obj->receiver_id = $receiver_id;
        $obj->external_link = $external_link;
        $obj->save();
        Log::info("Sent notification successfully");
        return "notification sent!";
    } catch (\Exception $e) {
        Log::info("Sent notification Error:" . $e->getMessage());
        return "notification error!";
    }
}

if (!function_exists('get_default_language')) {
    function get_default_language()
    {
        $language = MultiLanguage::where('default', STATUS_ACTIVE)->first();
        if ($language) {
            $iso_code = $language->iso_code;
            return $iso_code;
        }

        return 'en';
    }
}

if (!function_exists('get_currency_symbol')) {
    function get_currency_symbol()
    {
        $currency = Currency::where('current_currency', STATUS_ACTIVE)->first();
        if ($currency) {
            $symbol = $currency->symbol;
            return $symbol;
        }

        return '';
    }
}

if (!function_exists('get_currency_code')) {
    function get_currency_code()
    {
        $currency = Currency::where('current_currency', STATUS_ACTIVE)->first();
        if ($currency) {
            $currency_code = $currency->currency_code;
            return $currency_code;
        }

        return '';
    }
}

if (!function_exists('get_currency_placement')) {
    function get_currency_placement()
    {
        $currency = Currency::where('current_currency', STATUS_ACTIVE)->first();
        $placement = 'before';
        if ($currency) {
            $placement = $currency->currency_placement;
            return $placement;
        }

        return $placement;
    }
}


function getapisetting($coin_type, $property)
{
    $coin = Coin::join('api_settings', 'coins.id', '=', 'api_settings.coin_id')
        ->where('coins.coin_type', $coin_type)
        ->first([
            'coins.coin_type',
            'api_settings.*'
        ]);

    //    $coin = Coin::where('coin_type',$coin_type)->first();
    return $coin->{$property};
}

if (!function_exists('customNumberFormat')) {
    function customNumberFormat($value)
    {
        $number = explode('.', $value);
        if (!isset($number[1])) {
            return number_format($value, 8, '.', '');
        } else {
            $result = substr($number[1], 0, 8);
            if (strlen($result) < 8) {
                $result = number_format($value, 8, '.', '');
            } else {
                $result = $number[0] . "." . $result;
            }

            return $result;
        }
    }
}


if (!function_exists('calculateFees')) {
    function calculateFees($amount, $feeMethod, $feePercentage, $feeFixed)
    {
        try {
            if ($feeMethod == 1) {
                return customNumberFormat($feeFixed);
            } elseif ($feeMethod == 2) {
                return customNumberFormat(bcdiv(bcmul($feePercentage, $amount), 100));
            } elseif ($feeMethod == 3) {
                return customNumberFormat(bcadd($feeFixed, bcdiv(bcmul($feePercentage, $amount), 100)));
            } else {
                return 0;
            }
        } catch (\Exception $e) {
            return 0;
        }
    }
}


if (!function_exists('excluded_user')) {
    function excluded_user($param = null)
    {
        if ($param == null) {
            return ExcludedUser::all('user_id');
        }
        $userId = ExcludedUser::pluck('user_id')->toArray();

        return $userId;
    }
}

if (!function_exists('trade_max_level')) {
    function trade_max_level()
    {
        return 5;
    }
}

if (!function_exists('getPerCoinRate')) {
    function getPerCoinRate($coin_type)
    {
        return convertCurrencySwap(1, $coin_type, currentCurrency('currency_code'))["price"];
    }
}


if (!function_exists('allsetting')) {
    function allsetting($keys = null)
    {

        if ($keys && is_array($keys)) {
            $settings = Setting::whereIn('option_key', $keys)->pluck('option_value', 'option_key')->toArray();
            $settingsNotFoundInDB = array_fill_keys(array_diff($keys, array_keys($settings)), false);
            if (!empty($settingsNotFoundInDB)) {
                $settings = array_merge($settings, $settingsNotFoundInDB);
            }
            return $settings;
        } elseif ($keys && is_string($keys)) {
            $setting = Setting::where('option_key', $keys)->first();
            return empty($setting) ? false : $setting->value;
        }
        return Setting::pluck('option_value', 'option_key')->toArray();
    }
}


if (!function_exists('getRandomDecimal')) {
    function getRandomDecimal($min, $max, $probabilityRatio)
    {
        // Calculate the adjusted maximum value based on the probability ratio
        $adjustedMax = $max + ($max - $min) * ($probabilityRatio - 1);

        // Generate a random decimal number within the range
        $randomDecimal = mt_rand($min * 10000, $adjustedMax * 10000) / 10000;

        // Check if the random decimal number needs to be adjusted
        if ($randomDecimal > $max) {
            // Set the number to the maximum value
            $randomDecimal = $max;
        }

        return $randomDecimal;
    }
}

if (!function_exists('getReturnAmountRange')) {
    function getReturnAmountRange($userMining)
    {
        if ($userMining->userPlan->plan->return_type == RETURN_TYPE_RANDOM) {

            if (!is_null($userMining->user_hardware_id)) {
                $allHardware = Hardware::where('status', STATUS_ACTIVE)->orderBy('speed', 'ASC')->get();
                $maxSpeed = $allHardware->max('speed');
                $hardwareRange = [];
                foreach ($allHardware as $hardware) {
                    $hardwareRange[$hardware->id] = ($hardware->speed / $maxSpeed);
                }

                $max = ($userMining->userPlan->plan->max_return_amount_per_day * $hardwareRange[$userMining->userHardware->hardware_id]);
                return ['min' => $userMining->userPlan->plan->min_return_amount_per_day, 'max' => $max];
            }

            return ['min' => $userMining->userPlan->plan->min_return_amount_per_day, 'max' => $userMining->userPlan->plan->min_return_amount_per_day];
        }

        return ['min' => $userMining->userPlan->plan->return_amount_per_day, 'max' => $userMining->userPlan->plan->return_amount_per_day];
    }
}

if (!function_exists('getPlanEarningEstimation')) {
    function getPlanEarningEstimation($plan)
    {
        if ($plan->return_type == RETURN_TYPE_FIXED) {
            return $plan->return_amount_per_day . ' ' . $plan->coin->coin_type;
        } elseif ($plan->return_type == RETURN_TYPE_RANDOM) {
            return $plan->min_return_amount_per_day . ' ' . $plan->coin->coin_type . '-' . $plan->max_return_amount_per_day . ' ' . $plan->coin->coin_type;
        }
    }
}

if (!function_exists('getNotification')) {
    function getNotification($seen_status = null)
    {
        return Notification::where(function ($query) {
            $query->where('receiver_id', null)->orWhere('receiver_id', Auth::id());
        })->where('status', ACTIVE)
            ->where(function ($query) use ($seen_status) {
                if ($seen_status == NOTIFICATION_STATUS_SEEN) {
                    $query->where('seen_status', NOTIFICATION_STATUS_SEEN);
                } else if ($seen_status == NOTIFICATION_STATUS_UNSEEN) {
                    $query->where('seen_status', NOTIFICATION_STATUS_UNSEEN);
                }
            })
            ->orderBy('id', 'DESC')
            ->get();
    }
}

function get_clientIp()
{
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
}


function getEmailTemplate($category, $property, $link = null)
{
    $data = EmailTemplate::where('category', $category)->first();
    if ($data && $data != null) {
        if ($property == 'body') {
            $body = $data->{$property};
            foreach (emailTempFields() as $key => $item) {
                if ($key == '{{reset_password_url}}') {
                    $body = str_replace($key, $link, $body);
                }
                if ($key == '{{email_verify_url}}') {
                    $body = str_replace($key, $link, $body);
                }
                $body = str_replace($key, $item, $body);
            }
            return $body;
        } else {
            return $data->{$property};
        }
    }

    return '';

}

if (!function_exists('getPaymentStatusHtml')) {
    function getPaymentStatusHtml($status)
    {
        $html = '';
        if ($status == PAYMENT_STATUS_SUCCESS) {
            $html = '<p class="zBadge zBadge-active">' . __('Paid') . '</p>';
        } elseif ($status == PAYMENT_STATUS_CANCEL) {
            $html = '<p class="zBadge zBadge-deactivate">' . __('Canceled') . '</p>';
        } elseif ($status == PAYMENT_STATUS_PENDING) {
            $html = '<p class="zBadge zBadge-pending">' . __('Pending') . '</p>';
        }
        return $html;
    }
}

if (!function_exists('getUserStatusHtml')) {
    function getUserStatusHtml($status)
    {
        $html = '';
        if ($status == USER_STATUS_ACTIVE) {
            $html = '<p class="zBadge zBadge-active">' . __('Active') . '</p>';
        } elseif ($status == USER_STATUS_INACTIVE) {
            $html = '<p class="zBadge zBadge-deactivate">' . __('Inactive') . '</p>';
        } elseif ($status == USER_STATUS_UNVERIFIED) {
            $html = '<p class="zBadge zBadge-pending">' . __('Unverified') . '</p>';
        }
        return $html;
    }
}

if (!function_exists('getFileProperty')) {
    function getFileProperty($id, $property)
    {
        $file = FileHandler::find($id);
        $respose = null;
        if (!is_null($file)) {
            return $file->{$property};
        }
        return $respose;
    }
}

if (!function_exists('getStatusHtml')) {
    function getStatusHtml($status)
    {
        $html = '';
        if ($status == STATUS_ACTIVE) {
            $html = '<p class="zBadge zBadge-active">Active</p>';
        } else {
            $html = '<p class="zBadge zBadge-deactivate">Deactivate</p>';
        }
        return $html;
    }
}

if (!function_exists('getStatusForEnrolment')) {
    function getStatusForEnrolment($status)
    {
        $html = '';
        if ($status == ENROLMENT_APPROVED) {
            $html = '<p class="zBadge zBadge-active">Approved</p>';
        } elseif ($status == ENROLMENT_PENDING) {
            $html = '<p class="zBadge zBadge-deactivate bg-progress text-white">Pending</p>';
        } elseif ($status == ENROLMENT_RUNNING) {
            $html = '<p class="zBadge zBadge-deactivate bg-info text-body">Running</p>';
        } elseif ($status == ENROLMENT_CANCEL) {
            $html = '<p class="zBadge zBadge-deactivate text-cancel bg-danger">Cancel</p>';
        } elseif ($status == ENROLMENT_COMPLEATE) {
            $html = '<p class="zBadge zBadge-deactivate bg-green text-white">Compleate</p>';
        } else {
            $html = '<p class="zBadge zBadge-deactivate bg-black text-cancel">Close</p>';
        }
        return $html;
    }
}
if (!function_exists('getPayableAmountByPaymentId')) {
    function getPayableAmountByPaymentId($paymentId, $enrolmentId, $studentId)
    {
        $payableAmount = 0;
        $getPaymentData = Payment::where(['enrolment_id' => $enrolmentId, 'student_id' => $studentId, 'status' => PAYMENT_STATUS_SUCCESS])->get();
        if (!empty($getPaymentData)) {
            $totalPaidAmount = 0;
            foreach ($getPaymentData as $item) {
                if ($item->id == $paymentId) {
                    $payableAmount = $item->contact_amount - $totalPaidAmount;
                    break;
                }
                $totalPaidAmount = $totalPaidAmount + $item->amount;
            }
        }
        return $payableAmount;
    }
}

if (!function_exists('getDueAmountByPaymentId')) {
    function getDueAmountByPaymentId($paymentId, $enrolmentId, $studentId)
    {
        $dueAmount = 0;
        $getPaymentData = Payment::query()
            ->where(['enrolment_id' => $enrolmentId, 'student_id' => $studentId, 'status' => PAYMENT_STATUS_SUCCESS])->get();
        if (!empty($getPaymentData)) {
            $totalPaidAmount = 0;
            foreach ($getPaymentData as $key => $item) {
                if ($item->id == $paymentId) {
                    $totalPaidAmount = $totalPaidAmount + $item->amount;
                    $dueAmount = $item->contact_amount - $totalPaidAmount;
                    break;
                }
                $totalPaidAmount = $totalPaidAmount + $item->amount;
            }
        }
        return $dueAmount;
    }
}

if (!function_exists('generateUserActivityLog')) {
    function generateUserActivityLog($activity_name, $user_id)
    {
        $current_ip = get_clientIp();
        $agent = new Agent();
        $deviceType = isset($agent) && $agent->isMobile() == true ? 'Mobile' : 'Web';
        $location = geoip()->getLocation($current_ip);
        $activity['user_id'] = $user_id;
        $activity['activity'] = $activity_name;
        $activity['ip_address'] = isset($current_ip) ? $current_ip : '0.0.0.0';
        $activity['source'] = $deviceType;
        $activity['location'] = $location->country;
        UserActivity::create($activity);
    }
}
if (!function_exists('getUserData')) {
    function getUserData($id)
    {
       $user = User::find($id);
       if (!is_null($user)){
           return $user;
       }
       return null;
    }
}


