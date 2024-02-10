<?php

namespace App\Http\Controllers;

use App\Models\OffersAndSurveysLog;
use App\Models\OfferwallsSetting;
use App\Models\User;
use App\Models\Log;
use App\Models\Offerwall;
use Illuminate\Http\Request;
class OfferwallsPostbacksController extends Controller
{
    
    public function adscendmedia(Request $request)
    {

        $offerwall = Offerwall::where('name', 'Adscendmedia')->first();
     /*===================================Ip Whitelist and Check Offerwall Enabled or not==================================*/
        $ipAddress = $request->ip();
        // Get the whitelisted IPs from the database
        $whitelistedIps = json_decode($offerwall->whitelisted_ips, true);

        // Check if $ipAddress is in the whitelisted IPs
        if (in_array($ipAddress, $whitelistedIps)) {
            
        } else {
            // IP address is not whitelisted, take appropriate action
            return "Access Denied! IP address $ipAddress is not whitelisted!";
        }

        if ($offerwall->status != 1 ) {
            die();
            echo "offerwall is not enabled ";
        }
        /*===================================Get All common data from the postback==========================================================*/
        $uniqueUserId = $request->input('user_id');
        $payout = $request->input('payout');
        $currencyAmount = $request->input('reward');
        $transactionId = $request->input('transaction_id');
        $ipAddress = $request->has('ip') ? $request->input('ip') : null;
        $offerName = $request->has('offer_name') ? $request->input('offer_name') : null;
        $offerId = $request->has('offer_id') ? $request->input('offer_id') : null;
        $hash = $request->input('hash');

     /*===================================check the hash security==========================================================*/
        if(hash_hmac('md5', "offer_id=".$offerId."&payout=".$payout."&reward=".$currencyAmount."&transaction_id=".$transactionId."&ip=".$ipAddress, $offerwall->secret_key) !== $hash) {
            echo 0;
            die();
        }
     /*===================================Do necessary Calculations==========================================================*/
        $userId = User::where('unique_user_id', $uniqueUserId)->value('id');
        $user = User::find($userId);
        $refCommissionPercentage = $offerwall->ref_commission;
        $offerHold = $offerwall->hold;

        $userLevel = $user->level;
        $expertCp = $offerwall->expert_cp;
        if ( $userLevel == 0 ) 
        {
            $finalReward = ( $payout / 100 ) * $offerwall->starter_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 1 )
        {
            $finalReward = ( $payout / 100 ) * $offerwall->advance_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 2 ) {
            $finalReward = ( $payout / 100 ) * $offerwall->expert_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }else
        {
            Log::create([
                'user_id' => $userId, 
                'description' => 'user level is not specified',
            ]);
        }

        // if the transactionId starts with r- , it is a reverse transaction
        if (preg_match('/^r-(.*)/', $transactionId, $matches)) {
            // transactionId starts with "r-", $matches[1] contains the rest of the string
            $transactionId = $matches[1];
            $finalStatus = 0; //0=reversed
        }


        if( $request->has('status') ) 
        {
            if ($request->input('status') == 1 && $offerHold == 0) 
            {
                $finalStatus = 0; //0, completed, 1 on hold / pending, 2 reversed.
            }
            elseif ($request->input('status') == 1 && $finalReward <= $offerwall->tier1_hold_amount )
            {
                $finalStatus = 0;
            }
            elseif ( $request->input('status') == 1 && $offerHold == 1 && $offerwall->tier1_hold_amount < $finalReward && $currencyAmount > 0 && $payout > 0 )  //the complete status value of the provider offerwall
            {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 2;
            }
        }
        elseif ( $currencyAmount < 0 || $payout < 0)
        {
            $finalStatus = 2;
        }
        else
        {
            if ( $offerHold == 1 && $finalReward > $offerwall->tier1_hold_amount) {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 0;
            }
            
        }

        //check if user has any upline
        $uplineId = User::where('id', $userId)->value('upline');
        if( $uplineId != 0 )
        {
            $upline = User::find($uplineId);
            $uplineCommision = abs($finalReward) / 100 * $refCommissionPercentage;
        } else
        {
            $uplineCommision = 0;
        }

        //if hold is enabled, calculate the hold time based on the reward
        if ( $offerwall->hold == 1 ) {
            if (  $finalReward >= $offerwall->tier1_hold_amount && $finalReward < $offerwall->tier2_hold_amount ) {
                $offerHoldTime = $offerwall->tier1_hold_time;
            }elseif ( $finalReward >= $offerwall->tier2_hold_amount && $finalReward < $offerwall->tier3_hold_amount ) {
                $offerHoldTime = $offerwall->tier2_hold_time;
            }
            else{
                $offerHoldTime = $offerwall->tier3_hold_time;
            }
        }else{ $offerHoldTime =0; }
        //check if transaction found in the database
        $transactionIdExists = OffersAndSurveysLog::where('transaction_id', $transactionId)->where('provider_name', $offerwall->name)->exists();

     /*===================================Create Log if complete, update if trx found and reversed ==========================================================*/
        //if transaction does not exist, create log
         if (!$transactionIdExists) 
         {
            OffersAndSurveysLog::create([
                'user_id' => $userId,
                'provider_name' => $offerwall->name,
                'payout' => $payout,
                'reward' => $finalReward,
                'added_expert_level' => $addToExpertLevel,
                'upline_commision' => $uplineCommision,
                'transaction_id' => $transactionId,
                'offer_name' => $offerName,
                'offer_id' => $offerId,
                'hold_time' => $offerHoldTime,
                'instant_credit' => 0,    // 0 no, 1 yes,
                'ip_address' => $ipAddress,
                'status' => $finalStatus,
            ]);

            /*===================================reward or deduct user and upline==========================================================*/
            //if finalstatus = 0(completed), reward, and if 2(reversed), deduct the worker and upline and corresponding log
            if ( $finalStatus == 0 ) 
            {
                $user->addWorkerBalance($finalReward);
                $user->increment('diamond_level_balance', $addToExpertLevel);
                $user->increment('total_earned', $finalReward);
                $user->increment('earned_from_offers', $finalReward);
                $user->increment('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' => 'reward ' . $finalReward . ' added from Adscendmedia',
                ]);
                if($uplineId != 0)
                {
                    $uplineToReward = User::find($uplineId);
                    $uplineToReward->addWorkerBalance( $uplineCommision );
                    $uplineToReward->increment('earned_from_referrals', $uplineCommision);
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'received referral Commission ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }
            elseif ( $finalStatus == 2 ) 
            {
                $user->deductWorkerBalance(abs($finalReward));
                $user->decrement('diamond_level_balance', abs($addToExpertLevel));
                $user->decrement('total_earned', abs($finalReward));
                $user->decrement('earned_from_offers', abs($finalReward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' =>  $finalReward . ' reversed by Adscendmedia',
                ]);
                if($uplineId != 0)
                {
                    $uplineToDeduct = User::find($uplineId);
                    $uplineToDeduct->deductWorkerBalance( abs($uplineCommision) );
                    $uplineToDeduct->decrement('earned_from_referrals', abs($uplineCommision));
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'deducted chargedback ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }

         } elseif ( $transactionIdExists && $finalStatus == 2 )
         {
            $offerIdToReverse = OffersAndSurveysLog::where('transaction_id', $transactionId)->value('id');
            $offer = OffersAndSurveysLog::find($offerIdToReverse);
            $userIdToReverse = $offer->user_id;
            $user = User::find($userIdToReverse);
            if( $offer->status == 0 )
            {
                $user->deductWorkerBalance(abs($offer->reward));
                $user->decrement('diamond_level_balance', abs($offer->reward));
                $user->decrement('total_earned', abs($offer->reward));
                $user->decrement('earned_from_offers', abs($offer->reward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $user->id,
                    'description' =>  $offer->reward . ' reversed by Adscendmedia',
                ]);
            }

            if($uplineId != 0)
            {
                $uplineToDeduct = User::find($uplineId);
                $uplineToDeduct->deductWorkerBalance( abs($offer->upline_commision) );
                $uplineToDeduct->decrement('earned_from_referrals', abs($offer->upline_commision));
                Log::create([
                    'user_id' => $uplineId, 
                    'description' => 'deducted chargedback ' . $offer->upline_commision . ' from user '. $user->username,
                ]);
            }
            $offer->update([ 'status' => 2 ]);
        }
         else
         {
            $user->deductWorkerBalance(abs($finalReward));
            $user->decrement('diamond_level_balance', abs($addToExpertLevel));
            $user->decrement('total_earned', abs($finalReward));
            $user->decrement('earned_from_offers', abs($finalReward));
            $user->decrement('total_offers_completed');
         }

    
         echo 1;
       


    }


    public function ayetstudios(Request $request)
    {
        $offerwall = Offerwall::where('name', 'Ayetstudios')->first();
        /*===================================Ip Whitelist and Check Offerwall Enabled or not==================================*/ 
        $ipAddress = $request->ip();
        // Get the whitelisted IPs from the database
        $whitelistedIps = json_decode($offerwall->whitelisted_ips, true);

        // Check if $ipAddress is in the whitelisted IPs
        if (in_array($ipAddress, $whitelistedIps)) {
            
        } else {
            // IP address is not whitelisted, take appropriate action
            return "Access Denied! IP address $ipAddress is not whitelisted!";
        }

        if ($offerwall->status != 1 ) {
            die();
            echo "offerwall is not enabled ";
        }
        /*===================================Get All common data from the postback==========================================================*/
        $uniqueUserId = $request->input('user_id');
        $payout = $request->input('payout');
        $currencyAmount = $request->input('reward');
        $transactionId = $request->input('transaction_id');
        $ipAddress = $request->has('ip') ? $request->input('ip') : null;
        $offerName = $request->has('offer_name') ? $request->input('offer_name') : null;
        $offerId = $request->has('offer_id') ? $request->input('offer_id') : null;
        //status 0 conversion, 1 chargeback according to ayetstudios pub docs
        
     /*===================================check the hash security==========================================================*/
        $parameters = $request->all();
        // Order the parameters alphabetically
        ksort($parameters);
        // Concatenate the parameters into a string
        $parameterString = http_build_query($parameters);
        $computedHash = hash_hmac('sha256', $parameterString, $offerwall->api_key);
        // Get the received hash from the custom header
        $receivedHash = $request->header('x-ayetstudios-security-hash');
        // Compare the computed hash with the received hash
        if (hash_equals($computedHash, $receivedHash)){
            echo 1;
        }else
        {
            return response('Unauthorized', 401);
            die();
        }

     /*===================================Do necessary Calculations==========================================================*/


        $userId = User::where('unique_user_id', $uniqueUserId)->value('id');
        $user = User::find($userId);
        $refCommissionPercentage = $offerwall->ref_commission;
        $offerHold = $offerwall->hold;

        $userLevel = $user->level;
        $expertCp = $offerwall->expert_cp;
        if ( $userLevel == 0 ) 
        {
            $finalReward = ( $payout / 100 ) * $offerwall->starter_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 1 )
        {
            $finalReward = ( $payout / 100 ) * $offerwall->advance_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 2 ) {
            $finalReward = ( $payout / 100 ) * $offerwall->expert_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }else
        {
            Log::create([
                'user_id' => $userId, 
                'description' => 'user level is not specified',
            ]);
        }

        // if the transactionId starts with r- , it is a reverse transaction
        if (preg_match('/^r-(.*)/', $transactionId, $matches)) {
            // transactionId starts with "r-", $matches[1] contains the rest of the string
            $transactionId = $matches[1];
            $finalStatus = 0; //0=reversed
        }


        if( $request->has('status') ) 
        {
            if ($request->input('status') == 0 && $offerHold == 0 ) 
            {
                $finalStatus = 0; //0, completed, 1 on hold / pending, 2 reversed.
            }
            elseif ($request->input('status') == 0 && $finalReward <= $offerwall->tier1_hold_amount )
            {
                $finalStatus = 0;
            }
            elseif ( $request->input('status') == 0 && $offerHold == 1 && $offerwall->tier1_hold_amount < $finalReward && $currencyAmount > 0 && $payout > 0 )  //the complete status value of the provider offerwall
            {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 2;
            }
        }
        elseif ( $currencyAmount < 0 || $payout < 0)
        {
            $finalStatus = 2;
        }
        else
        {
            if ( $offerHold == 1 && $finalReward > $offerwall->tier1_hold_amount) {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 0;
            }
            
        }

        //check if user has any upline
        $uplineId = User::where('id', $userId)->value('upline');
        if( $uplineId != 0 )
        {
            $upline = User::find($uplineId);
            $uplineCommision = abs($finalReward) / 100 * $refCommissionPercentage;
        } else
        {
            $uplineCommision = 0;
        }
        

        //if hold is enabled, calculate the hold time based on the reward
        if ( $offerwall->hold == 1 ) {
            if (  $finalReward >= $offerwall->tier1_hold_amount && $finalReward < $offerwall->tier2_hold_amount ) {
                $offerHoldTime = $offerwall->tier1_hold_time;
            }elseif ( $finalReward >= $offerwall->tier2_hold_amount && $finalReward < $offerwall->tier3_hold_amount ) {
                $offerHoldTime = $offerwall->tier2_hold_time;
            }
            else{
                $offerHoldTime = $offerwall->tier3_hold_time;
            }
        }else{ $offerHoldTime =0; }
        //check if transaction found in the database
        $transactionIdExists = OffersAndSurveysLog::where('transaction_id', $transactionId)->where('provider_name', $offerwall->name)->exists();

     /*===================================Create Log if complete, update if trx found and reversed ==========================================================*/
        //if transaction does not exist, create log
         if (!$transactionIdExists) 
         {
            OffersAndSurveysLog::create([
                'user_id' => $userId,
                'provider_name' => $offerwall->name,
                'payout' => $payout,
                'reward' => $finalReward,
                'added_expert_level' => $addToExpertLevel,
                'upline_commision' => $uplineCommision,
                'transaction_id' => $transactionId,
                'offer_name' => $offerName,
                'offer_id' => $offerId,
                'hold_time' => $offerHoldTime,
                'instant_credit' => 0,    // 0 no, 1 yes,
                'ip_address' => $ipAddress,
                'status' => $finalStatus,
            ]);

            /*===================================reward or deduct user and upline==========================================================*/
            //if finalstatus = 0(completed), reward, and if 2(reversed), deduct the worker and upline and corresponding log
            if ( $finalStatus == 0 ) 
            {
                $user->addWorkerBalance($finalReward);
                $user->increment('diamond_level_balance', $addToExpertLevel);
                $user->increment('total_earned', $finalReward);
                $user->increment('earned_from_offers', $finalReward);
                $user->increment('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' => 'reward ' . $finalReward . ' added from ayetstudios',
                ]);
                if($uplineId != 0)
                {
                    $uplineToReward = User::find($uplineId);
                    $uplineToReward->addWorkerBalance( $uplineCommision );
                    $uplineToReward->increment('earned_from_referrals', $uplineCommision);
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'received referral Commission ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }
            elseif ( $finalStatus == 2 ) 
            {
                $user->deductWorkerBalance(abs($finalReward));
                $user->decrement('diamond_level_balance', abs($addToExpertLevel));
                $user->decrement('total_earned', abs($finalReward));
                $user->decrement('earned_from_offers', abs($finalReward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' =>  $finalReward . ' reversed by ayetstudios',
                ]);
                if($uplineId != 0)
                {
                    $uplineToDeduct = User::find($uplineId);
                    $uplineToDeduct->deductWorkerBalance( abs($uplineCommision) );
                    $uplineToDeduct->decrement('earned_from_referrals', abs($uplineCommision));
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'deducted chargedback ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }

         } elseif ( $transactionIdExists && $finalStatus == 2 )
         {
            $offerIdToReverse = OffersAndSurveysLog::where('transaction_id', $transactionId)->value('id');
            $offer = OffersAndSurveysLog::find($offerIdToReverse);
            $userIdToReverse = $offer->user_id;
            $user = User::find($userIdToReverse);
            if( $offer->status == 0 )
            {
                $user->deductWorkerBalance(abs($offer->reward));
                $user->decrement('diamond_level_balance', abs($offer->reward));
                $user->decrement('total_earned', abs($offer->reward));
                $user->decrement('earned_from_offers', abs($offer->reward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $user->id,
                    'description' =>  $offer->reward . ' reversed by ayetstudios',
                ]);
            }

            if($uplineId != 0)
            {
                $uplineToDeduct = User::find($uplineId);
                $uplineToDeduct->deductWorkerBalance( abs($offer->upline_commision) );
                $uplineToDeduct->decrement('earned_from_referrals', abs($offer->upline_commision));
                Log::create([
                    'user_id' => $uplineId, 
                    'description' => 'deducted chargedback ' . $offer->upline_commision . ' from user '. $user->username,
                ]);
            }
            $offer->update([ 'status' => 2 ]);
        }
         else
         {
            $user->deductWorkerBalance(abs($finalReward));
            $user->decrement('diamond_level_balance', abs($addToExpertLevel));
            $user->decrement('total_earned', abs($finalReward));
            $user->decrement('earned_from_offers', abs($finalReward));
            $user->decrement('total_offers_completed');
         }
         echo 1;
    }



    public function adbreakmedia(Request $request)
    {
        $offerwall = Offerwall::where('name', 'Adbreakmedia')->first();
        /*===================================Ip Whitelist and Check Offerwall Enabled or not==================================*/
        $ipAddress = $request->ip();
        $whitelistedIps = json_decode($offerwall->whitelisted_ips, true);
        if (in_array($ipAddress, $whitelistedIps)) {
            
        } else {
            return "Access Denied! IP address $ipAddress is not whitelisted!";
        }
        if ($offerwall->status != 1 ) {
            die();
            echo "offerwall is not enabled ";
        }
        /*===================================Get All common data from the postback==========================================================*/
        $uniqueUserId = $request->input('user_id');
        $payout = $request->input('payout');
        $currencyAmount = $request->input('reward');
        $transactionId = $request->input('transaction_id');
        $ipAddress = $request->has('ip') ? $request->input('ip') : null;
        $offerName = $request->has('offer_name') ? $request->input('offer_name') : null;
        $offerId = $request->has('offer_id') ? $request->input('offer_id') : null;
        $hash = $request->input('hash');
        //status = completed, or rejected value according to adbreakmedia docs
     /*===================================check the hash security==========================================================*/
        $calculated_hash = hash('sha256', $uniqueUserId . $offerId . $transactionId . $offerwall->secret_key);
 
        if ($hash === $calculated_hash) {
            // success
            http_response_code(200);
            echo "Approved";
        } else {
            http_response_code(400);
            echo "Unauthorized";
            return;
            die();
        }

     /*===================================Do necessary Calculations==========================================================*/


        $userId = User::where('unique_user_id', $uniqueUserId)->value('id');
        $user = User::find($userId);
        $refCommissionPercentage = $offerwall->ref_commission;
        $offerHold = $offerwall->hold;

        $userLevel = $user->level;
        $expertCp = $offerwall->expert_cp;
        if ( $userLevel == 0 ) 
        {
            $finalReward = ( $payout / 100 ) * $offerwall->starter_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 1 )
        {
            $finalReward = ( $payout / 100 ) * $offerwall->advance_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 2 ) {
            $finalReward = ( $payout / 100 ) * $offerwall->expert_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }else
        {
            Log::create([
                'user_id' => $userId, 
                'description' => 'user level is not specified',
            ]);
        }

        // if the transactionId starts with r- , it is a reverse transaction
        if (preg_match('/^r-(.*)/', $transactionId, $matches)) {
            // transactionId starts with "r-", $matches[1] contains the rest of the string
            $transactionId = $matches[1];
            $finalStatus = 0; //0=reversed
        }


        if( $request->has('status') ) 
        {
            if ($request->input('status') == 'completed' && $offerHold == 0 ) 
            {
                $finalStatus = 0; //0, completed, 1 on hold / pending, 2 reversed.
            }
            elseif ($request->input('status') == 'completed' && $finalReward <= $offerwall->tier1_hold_amount )
            {
                $finalStatus = 0;
            }
            elseif ( $request->input('status') == 'completed' && $offerHold == 1 && $offerwall->tier1_hold_amount < $finalReward && $currencyAmount > 0 && $payout > 0 )  //the complete status value of the provider offerwall
            {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 2;
            }
        }
        elseif ( $currencyAmount < 0 || $payout < 0)
        {
            $finalStatus = 2;
        }
        else
        {
            if ( $offerHold == 1 && $finalReward > $offerwall->tier1_hold_amount) {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 0;
            }
            
        }

        //check if user has any upline
        $uplineId = User::where('id', $userId)->value('upline');
        if( $uplineId != 0 )
        {
            $upline = User::find($uplineId);
            $uplineCommision = abs($finalReward) / 100 * $refCommissionPercentage;
        } else
        {
            $uplineCommision = 0;
        }
        

        //if hold is enabled, calculate the hold time based on the reward
        if ( $offerwall->hold == 1 ) {
            if (  $finalReward >= $offerwall->tier1_hold_amount && $finalReward < $offerwall->tier2_hold_amount ) {
                $offerHoldTime = $offerwall->tier1_hold_time;
            }elseif ( $finalReward >= $offerwall->tier2_hold_amount && $finalReward < $offerwall->tier3_hold_amount ) {
                $offerHoldTime = $offerwall->tier2_hold_time;
            }
            else{
                $offerHoldTime = $offerwall->tier3_hold_time;
            }
        }else{ $offerHoldTime =0; }
        //check if transaction found in the database
        $transactionIdExists = OffersAndSurveysLog::where('transaction_id', $transactionId)->where('provider_name', $offerwall->name)->exists();

     /*===================================Create Log if complete, update if trx found and reversed ==========================================================*/
        //if transaction does not exist, create log
         if (!$transactionIdExists) 
         {
            OffersAndSurveysLog::create([
                'user_id' => $userId,
                'provider_name' => $offerwall->name,
                'payout' => $payout,
                'reward' => $finalReward,
                'added_expert_level' => $addToExpertLevel,
                'upline_commision' => $uplineCommision,
                'transaction_id' => $transactionId,
                'offer_name' => $offerName,
                'offer_id' => $offerId,
                'hold_time' => $offerHoldTime,
                'instant_credit' => 0,    // 0 no, 1 yes,
                'ip_address' => $ipAddress,
                'status' => $finalStatus,
            ]);

            /*===================================reward or deduct user and upline==========================================================*/
            //if finalstatus = 0(completed), reward, and if 2(reversed), deduct the worker and upline and corresponding log
            if ( $finalStatus == 0 ) 
            {
                $user->addWorkerBalance($finalReward);
                $user->increment('diamond_level_balance', $addToExpertLevel);
                $user->increment('total_earned', $finalReward);
                $user->increment('earned_from_offers', $finalReward);
                $user->increment('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' => 'reward ' . $finalReward . ' added from adbreakmedia',
                ]);
                if($uplineId != 0)
                {
                    $uplineToReward = User::find($uplineId);
                    $uplineToReward->addWorkerBalance( $uplineCommision );
                    $uplineToReward->increment('earned_from_referrals', $uplineCommision);
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'received referral Commission ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }
            elseif ( $finalStatus == 2 ) 
            {
                $user->deductWorkerBalance(abs($finalReward));
                $user->decrement('diamond_level_balance', abs($addToExpertLevel));
                $user->decrement('total_earned', abs($finalReward));
                $user->decrement('earned_from_offers', abs($finalReward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' =>  $finalReward . ' reversed by adbreakmedia',
                ]);
                if($uplineId != 0)
                {
                    $uplineToDeduct = User::find($uplineId);
                    $uplineToDeduct->deductWorkerBalance( abs($uplineCommision) );
                    $uplineToDeduct->decrement('earned_from_referrals', abs($uplineCommision));
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'deducted chargedback ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }

         } elseif ( $transactionIdExists && $finalStatus == 2 )
         {
            $offerIdToReverse = OffersAndSurveysLog::where('transaction_id', $transactionId)->value('id');
            $offer = OffersAndSurveysLog::find($offerIdToReverse);
            $userIdToReverse = $offer->user_id;
            $user = User::find($userIdToReverse);
            if( $offer->status == 0 )
            {
                $user->deductWorkerBalance(abs($offer->reward));
                $user->decrement('diamond_level_balance', abs($offer->reward));
                $user->decrement('total_earned', abs($offer->reward));
                $user->decrement('earned_from_offers', abs($offer->reward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $user->id,
                    'description' =>  $offer->reward . ' reversed by adbreakmedia',
                ]);
            }

            if($uplineId != 0)
            {
                $uplineToDeduct = User::find($uplineId);
                $uplineToDeduct->deductWorkerBalance( abs($offer->upline_commision) );
                $uplineToDeduct->decrement('earned_from_referrals', abs($offer->upline_commision));
                Log::create([
                    'user_id' => $uplineId, 
                    'description' => 'deducted chargedback ' . $offer->upline_commision . ' from user '. $user->username,
                ]);
            }
            $offer->update([ 'status' => 2 ]);
        }
         else
         {
            $user->deductWorkerBalance(abs($finalReward));
            $user->decrement('diamond_level_balance', abs($addToExpertLevel));
            $user->decrement('total_earned', abs($finalReward));
            $user->decrement('earned_from_offers', abs($finalReward));
            $user->decrement('total_offers_completed');
         }       
        echo 1;
        die();
    }



    public function bitlabs(Request $request)
    {
        $offerwall = Offerwall::where('name', 'Bitlabs')->first();
        /*===================================Ip Whitelist and Check Offerwall Enabled or not==================================*/
        // $ipAddress = $request->ip();
        // Get the whitelisted IPs from the database
        // $whitelistedIps = json_decode($offerwall->whitelisted_ips, true);

        // // Check if $ipAddress is in the whitelisted IPs
        // if (in_array($ipAddress, $whitelistedIps)) {
            
        // } else {
        //     // IP address is not whitelisted, take appropriate action
        //     return "Access Denied! IP address $ipAddress is not whitelisted!";
        // }

        // if ($offerwall->status != 1 ) {
        //     die();
        //     echo "offerwall is not enabled ";
        // }
        /*===================================Get All common data from the postback==========================================================*/
        $uniqueUserId = $request->input('user_id');
        $payout = $request->input('payout');
        $currencyAmount = $request->input('reward');
        $transactionId = $request->input('transaction_id');
        $ipAddress = $request->has('ip') ? $request->input('ip') : null;
        $offerName = $request->has('offer_name') ? $request->input('offer_name') : null;
        $offerId = $request->has('offer_id') ? $request->input('offer_id') : null;
        $hash = $request->input('hash');
        
     /*===================================check the hash security==========================================================*/
        // Get the currently active http protocol
        $protocol = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http";
        // Build the full callback URL
        // Example: https://url.com?param1=foo&param2=bar&hash=3171f6b78e06cadcec4c9c3b15f858b8400e8738
        $url = "$protocol://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        // Save all query parameters of the callback into the $params array
        $url_components = parse_url($url);
        parse_str($url_components["query"], $params);
        // Get the callback URL without the "hash" query parameter
        // Example: https://url.com?param1=foo&param2=bar
        $url_val = substr($url, 0, -strlen("&hash=$params[hash]"));
        // Generate a hash from the complete callback URL without the "hash" query parameter
        $calculatedHash = hash_hmac("sha1", $url_val, $offerwall->secret_key);
            
        //Check if the generated hash is the same as the "hash" query parameter
        if ($calculatedHash === $hash) {
          echo "valid";
        } else {
          echo "invalid";
          exit(0);
        }

     /*===================================Do necessary Calculations==========================================================*/


        $userId = User::where('unique_user_id', $uniqueUserId)->value('id');
        $user = User::find($userId);
        $refCommissionPercentage = $offerwall->ref_commission;
        $offerHold = $offerwall->hold;

        $userLevel = $user->level;
        $expertCp = $offerwall->expert_cp;
        if ( $userLevel == 0 ) 
        {
            $finalReward = ( $payout / 100 ) * $offerwall->starter_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 1 )
        {
            $finalReward = ( $payout / 100 ) * $offerwall->advance_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 2 ) {
            $finalReward = ( $payout / 100 ) * $offerwall->expert_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }else
        {
            Log::create([
                'user_id' => $userId, 
                'description' => 'user level is not specified',
            ]);
        }

        // if the transactionId starts with r- , it is a reverse transaction
        if (preg_match('/^r-(.*)/', $transactionId, $matches)) {
            // transactionId starts with "r-", $matches[1] contains the rest of the string
            $transactionId = $matches[1];
            $finalStatus = 0; //0=reversed
        }


        if( $request->has('status') ) 
        {
            if ($request->input('status') == 'COMPLETE' && $offerHold == 0 ) 
            {
                $finalStatus = 0; //0, completed, 1 on hold / pending, 2 reversed.
            }
            elseif ($request->input('status') == 'COMPLETE' && $finalReward <= $offerwall->tier1_hold_amount )
            {
                $finalStatus = 0;
            }
            elseif ( $request->input('status') == 'COMPLETE' && $offerHold == 1 && $offerwall->tier1_hold_amount < $finalReward && $currencyAmount > 0 && $payout > 0 )  //the complete status value of the provider offerwall
            {
                $finalStatus = 1;
            }
            elseif( $request->input('status') == 'PENDING' ){
                return;
            }else{
                $finalStatus = 2;
            }
        }
        elseif ( $currencyAmount < 0 || $payout < 0)
        {
            $finalStatus = 2;
        }
        else
        {
            if ( $offerHold == 1 && $finalReward > $offerwall->tier1_hold_amount) {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 0;
            }
            
        }

        //check if user has any upline
        $uplineId = User::where('id', $userId)->value('upline');
        if( $uplineId != 0 )
        {
            $upline = User::find($uplineId);
            $uplineCommision = abs($finalReward) / 100 * $refCommissionPercentage;
        } else
        {
            $uplineCommision = 0;
        }
        

        //if hold is enabled, calculate the hold time based on the reward
        if ( $offerwall->hold == 1 ) {
            if (  $finalReward >= $offerwall->tier1_hold_amount && $finalReward < $offerwall->tier2_hold_amount ) {
                $offerHoldTime = $offerwall->tier1_hold_time;
            }elseif ( $finalReward >= $offerwall->tier2_hold_amount && $finalReward < $offerwall->tier3_hold_amount ) {
                $offerHoldTime = $offerwall->tier2_hold_time;
            }
            else{
                $offerHoldTime = $offerwall->tier3_hold_time;
            }
        }else{ $offerHoldTime =0; }
        //check if transaction found in the database
        $transactionIdExists = OffersAndSurveysLog::where('transaction_id', $transactionId)->where('provider_name', $offerwall->name)->exists();

     /*===================================Create Log if complete, update if trx found and reversed ==========================================================*/
        //if transaction does not exist, create log
         if (!$transactionIdExists) 
         {
            OffersAndSurveysLog::create([
                'user_id' => $userId,
                'provider_name' => $offerwall->name,
                'payout' => $payout,
                'reward' => $finalReward,
                'added_expert_level' => $addToExpertLevel,
                'upline_commision' => $uplineCommision,
                'transaction_id' => $transactionId,
                'offer_name' => $offerName,
                'offer_id' => $offerId,
                'hold_time' => $offerHoldTime,
                'instant_credit' => 0,    // 0 no, 1 yes,
                'ip_address' => $ipAddress,
                'status' => $finalStatus,
            ]);

            /*===================================reward or deduct user and upline==========================================================*/
            //if finalstatus = 0(completed), reward, and if 2(reversed), deduct the worker and upline and corresponding log
            if ( $finalStatus == 0 ) 
            {
                $user->addWorkerBalance($finalReward);
                $user->increment('diamond_level_balance', $addToExpertLevel);
                $user->increment('total_earned', $finalReward);
                $user->increment('earned_from_offers', $finalReward);
                $user->increment('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' => 'reward ' . $finalReward . ' added from bitlabs',
                ]);
                if($uplineId != 0)
                {
                    $uplineToReward = User::find($uplineId);
                    $uplineToReward->addWorkerBalance( $uplineCommision );
                    $uplineToReward->increment('earned_from_referrals', $uplineCommision);
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'received referral Commission ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }
            elseif ( $finalStatus == 2 ) 
            {
                $user->deductWorkerBalance(abs($finalReward));
                $user->decrement('diamond_level_balance', abs($addToExpertLevel));
                $user->decrement('total_earned', abs($finalReward));
                $user->decrement('earned_from_offers', abs($finalReward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' =>  $finalReward . ' reversed by bitlabs',
                ]);
                if($uplineId != 0)
                {
                    $uplineToDeduct = User::find($uplineId);
                    $uplineToDeduct->deductWorkerBalance( abs($uplineCommision) );
                    $uplineToDeduct->decrement('earned_from_referrals', abs($uplineCommision));
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'deducted chargedback ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }

         } elseif ( $transactionIdExists && $finalStatus == 2 )
         {
            $offerIdToReverse = OffersAndSurveysLog::where('transaction_id', $transactionId)->value('id');
            $offer = OffersAndSurveysLog::find($offerIdToReverse);
            $userIdToReverse = $offer->user_id;
            $user = User::find($userIdToReverse);
            if( $offer->status == 0 )
            {
                $user->deductWorkerBalance(abs($offer->reward));
                $user->decrement('diamond_level_balance', abs($offer->reward));
                $user->decrement('total_earned', abs($offer->reward));
                $user->decrement('earned_from_offers', abs($offer->reward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $user->id,
                    'description' =>  $offer->reward . ' reversed by bitlabs',
                ]);
            }

            if($uplineId != 0)
            {
                $uplineToDeduct = User::find($uplineId);
                $uplineToDeduct->deductWorkerBalance( abs($offer->upline_commision) );
                $uplineToDeduct->decrement('earned_from_referrals', abs($offer->upline_commision));
                Log::create([
                    'user_id' => $uplineId, 
                    'description' => 'deducted chargedback ' . $offer->upline_commision . ' from user '. $user->username,
                ]);
            }
            $offer->update([ 'status' => 2 ]);
        }
         else
         {
            $user->deductWorkerBalance(abs($finalReward));
            $user->decrement('diamond_level_balance', abs($addToExpertLevel));
            $user->decrement('total_earned', abs($finalReward));
            $user->decrement('earned_from_offers', abs($finalReward));
            $user->decrement('total_offers_completed');
         }    
    }


    public function bitcotasks(Request $request)
    {
        $offerwall = Offerwall::where('name', 'Bitcotasks')->first();
        /*===================================Ip Whitelist and Check Offerwall Enabled or not==================================*/
        $ipAddress = $request->ip();
        // Get the whitelisted IPs from the database
        $whitelistedIps = json_decode($offerwall->whitelisted_ips, true);

        // Check if $ipAddress is in the whitelisted IPs
        if (in_array($ipAddress, $whitelistedIps)) {
            
        } else {
            // IP address is not whitelisted, take appropriate action
            return "Access Denied! IP address $ipAddress is not whitelisted!";
        }

        if ($offerwall->status != 1 ) {
            die();
            echo "offerwall is not enabled ";
        }
        /*===================================Get All common data from the postback==========================================================*/
        $uniqueUserId = $request->input('subId');
        $payout = $request->input('payout');
        $currencyAmount = $request->input('reward');
        $transactionId = $request->input('transId');
        $ipAddress = $request->has('userIp') ? $request->input('userIp') : null;
        $offerName = $request->has('offer_name') ? $request->input('offer_name') : null;
        $offerId = $request->has('offer_id') ? $request->input('offer_id') : null;
        $hash = $request->input('signature');
        
         /*===================================check the hash security==========================================================*/
         $secretKey = $offerwall->secret_key;
         if(md5($uniqueUserId.$transactionId.$currencyAmount.$secretKey) != $hash)
         {
            echo "ERROR: Signature doesn't match";
             return;
         }

        /*===================================Do necessary Calculations==========================================================*/


        $userId = User::where('unique_user_id', $uniqueUserId)->value('id');
        $user = User::find($userId);
        $refCommissionPercentage = $offerwall->ref_commission;
        $offerHold = $offerwall->hold;

        $userLevel = $user->level;
        $expertCp = $offerwall->expert_cp;
        if ( $userLevel == 0 ) 
        {
            $finalReward = ( $payout / 100 ) * $offerwall->starter_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 1 )
        {
            $finalReward = ( $payout / 100 ) * $offerwall->advance_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 2 ) {
            $finalReward = ( $payout / 100 ) * $offerwall->expert_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }else
        {
            Log::create([
                'user_id' => $userId, 
                'description' => 'user level is not specified',
            ]);
        }

        // if the transactionId starts with r- , it is a reverse transaction
        if (preg_match('/^r-(.*)/', $transactionId, $matches)) {
            // transactionId starts with "r-", $matches[1] contains the rest of the string
            $transactionId = $matches[1];
            $finalStatus = 0; //0=reversed
        }


        if( $request->has('status') ) 
        {
            if ($request->input('status') == 1 && $offerHold == 0 ) 
            {
                $finalStatus = 0; //0, completed, 1 on hold / pending, 2 reversed.
            }
            elseif ($request->input('status') == 1 && $finalReward <= $offerwall->tier1_hold_amount )
            {
                $finalStatus = 0;
            }
            elseif ( $request->input('status') == 1 && $offerHold == 1 && $offerwall->tier1_hold_amount < $finalReward && $currencyAmount > 0 && $payout > 0 )  //the complete status value of the provider offerwall
            {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 2;
            }
        }
        elseif ( $currencyAmount < 0 || $payout < 0)
        {
            $finalStatus = 2;
        }
        else
        {
            if ( $offerHold == 1 && $finalReward > $offerwall->tier1_hold_amount) {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 0;
            }
            
        }

        //check if user has any upline
        $uplineId = User::where('id', $userId)->value('upline');
        if( $uplineId != 0 )
        {
            $upline = User::find($uplineId);
            $uplineCommision = abs($finalReward) / 100 * $refCommissionPercentage;
        } else
        {
            $uplineCommision = 0;
        }
        

        //if hold is enabled, calculate the hold time based on the reward
        if ( $offerwall->hold == 1 ) {
            if (  $finalReward >= $offerwall->tier1_hold_amount && $finalReward < $offerwall->tier2_hold_amount ) {
                $offerHoldTime = $offerwall->tier1_hold_time;
            }elseif ( $finalReward >= $offerwall->tier2_hold_amount && $finalReward < $offerwall->tier3_hold_amount ) {
                $offerHoldTime = $offerwall->tier2_hold_time;
            }
            else{
                $offerHoldTime = $offerwall->tier3_hold_time;
            }
        }else{ $offerHoldTime =0; }
        //check if transaction found in the database
        $transactionIdExists = OffersAndSurveysLog::where('transaction_id', $transactionId)->where('provider_name', $offerwall->name)->exists();

        /*===================================Create Log if complete, update if trx found and reversed ==========================================================*/
        //if transaction does not exist, create log
         if (!$transactionIdExists) 
         {
            OffersAndSurveysLog::create([
                'user_id' => $userId,
                'provider_name' => $offerwall->name,
                'payout' => $payout,
                'reward' => $finalReward,
                'added_expert_level' => $addToExpertLevel,
                'upline_commision' => $uplineCommision,
                'transaction_id' => $transactionId,
                'offer_name' => $offerName,
                'offer_id' => $offerId,
                'hold_time' => $offerHoldTime,
                'instant_credit' => 0,    // 0 no, 1 yes,
                'ip_address' => $ipAddress,
                'status' => $finalStatus,
            ]);

            /*===================================reward or deduct user and upline==========================================================*/
            //if finalstatus = 0(completed), reward, and if 2(reversed), deduct the worker and upline and corresponding log
            if ( $finalStatus == 0 ) 
            {
                $user->addWorkerBalance($finalReward);
                $user->increment('diamond_level_balance', $addToExpertLevel);
                $user->increment('total_earned', $finalReward);
                $user->increment('earned_from_offers', $finalReward);
                $user->increment('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' => 'reward ' . $finalReward . ' added from bitcotasks',
                ]);
                if($uplineId != 0)
                {
                    $uplineToReward = User::find($uplineId);
                    $uplineToReward->addWorkerBalance( $uplineCommision );
                    $uplineToReward->increment('earned_from_referrals', $uplineCommision);
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'received referral Commission ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }
            elseif ( $finalStatus == 2 ) 
            {
                $user->deductWorkerBalance(abs($finalReward));
                $user->decrement('diamond_level_balance', abs($addToExpertLevel));
                $user->decrement('total_earned', abs($finalReward));
                $user->decrement('earned_from_offers', abs($finalReward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' =>  $finalReward . ' reversed by bitcotasks',
                ]);
                if($uplineId != 0)
                {
                    $uplineToDeduct = User::find($uplineId);
                    $uplineToDeduct->deductWorkerBalance( abs($uplineCommision) );
                    $uplineToDeduct->decrement('earned_from_referrals', abs($uplineCommision));
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'deducted chargedback ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }

         } elseif ( $transactionIdExists && $finalStatus == 2 )
         {
            $offerIdToReverse = OffersAndSurveysLog::where('transaction_id', $transactionId)->value('id');
            $offer = OffersAndSurveysLog::find($offerIdToReverse);
            $userIdToReverse = $offer->user_id;
            $user = User::find($userIdToReverse);
            if( $offer->status == 0 )
            {
                $user->deductWorkerBalance(abs($offer->reward));
                $user->decrement('diamond_level_balance', abs($offer->reward));
                $user->decrement('total_earned', abs($offer->reward));
                $user->decrement('earned_from_offers', abs($offer->reward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $user->id,
                    'description' =>  $offer->reward . ' reversed by bitcotasks',
                ]);
            }

            if($uplineId != 0)
            {
                $uplineToDeduct = User::find($uplineId);
                $uplineToDeduct->deductWorkerBalance( abs($offer->upline_commision) );
                $uplineToDeduct->decrement('earned_from_referrals', abs($offer->upline_commision));
                Log::create([
                    'user_id' => $uplineId, 
                    'description' => 'deducted chargedback ' . $offer->upline_commision . ' from user '. $user->username,
                ]);
            }
            $offer->update([ 'status' => 2 ]);
        }
         else
         {
            $user->deductWorkerBalance(abs($finalReward));
            $user->decrement('diamond_level_balance', abs($addToExpertLevel));
            $user->decrement('total_earned', abs($finalReward));
            $user->decrement('earned_from_offers', abs($finalReward));
            $user->decrement('total_offers_completed');
         }
         echo 1;
         die();
    }



    public function cpxresearch(Request $request)
    {
        $offerwall = Offerwall::where('name', 'Cpxresearch')->first();
        /*===================================Ip Whitelist and Check Offerwall Enabled or not==================================*/
        $ipAddress = $request->ip();
        // Get the whitelisted IPs from the database
        $whitelistedIps = json_decode($offerwall->whitelisted_ips, true);

        // Check if $ipAddress is in the whitelisted IPs
        if (in_array($ipAddress, $whitelistedIps)) {
            
        } else {
            // IP address is not whitelisted, take appropriate action
            return "Access Denied! IP address $ipAddress is not whitelisted!";
        }

        if ($offerwall->status != 1 ) {
            die();
            echo "offerwall is not enabled ";
        }
        /*===================================Get All common data from the postback==========================================================*/
        $uniqueUserId = $request->input('user_id');
        $payout = $request->input('payout');
        $currencyAmount = $request->input('reward');
        $transactionId = $request->input('transaction_id');
        $ipAddress = $request->has('ip') ? $request->input('ip') : null;
        $offerName = $request->has('offer_name') ? $request->input('offer_name') : null;
        $offerId = $request->has('offer_id') ? $request->input('offer_id') : null;
        $hash = $request->input('hash');
        
         /*===================================check the hash security==========================================================*/
         $secret_key = md5($transactionId.'-' . $offerwall->secret_key);
         if($secret_key !== $hash){
             echo 0;
             die();
         }

        /*===================================Do necessary Calculations==========================================================*/


        $userId = User::where('unique_user_id', $uniqueUserId)->value('id');
        $user = User::find($userId);
        $refCommissionPercentage = $offerwall->ref_commission;
        $offerHold = $offerwall->hold;

        $userLevel = $user->level;
        $expertCp = $offerwall->expert_cp;
        if ( $userLevel == 0 ) 
        {
            $finalReward = ( $payout / 100 ) * $offerwall->starter_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 1 )
        {
            $finalReward = ( $payout / 100 ) * $offerwall->advance_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 2 ) {
            $finalReward = ( $payout / 100 ) * $offerwall->expert_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }else
        {
            Log::create([
                'user_id' => $userId, 
                'description' => 'user level is not specified',
            ]);
        }

        // if the transactionId starts with r- , it is a reverse transaction
        if (preg_match('/^r-(.*)/', $transactionId, $matches)) {
            // transactionId starts with "r-", $matches[1] contains the rest of the string
            $transactionId = $matches[1];
            $finalStatus = 0; //0=reversed
        }


        if( $request->has('status') ) 
        {
            if ($request->input('status') == 1 && $offerHold == 0 ) 
            {
                $finalStatus = 0; //0, completed, 1 on hold / pending, 2 reversed.
            }
            elseif ($request->input('status') == 1 && $finalReward <= $offerwall->tier1_hold_amount )
            {
                $finalStatus = 0;
            }
            elseif ( $request->input('status') == 1 && $offerHold == 1 && $offerwall->tier1_hold_amount < $finalReward && $currencyAmount > 0 && $payout > 0 )  //the complete status value of the provider offerwall
            {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 2;
            }
        }
        elseif ( $currencyAmount < 0 || $payout < 0)
        {
            $finalStatus = 2;
        }
        else
        {
            if ( $offerHold == 1 && $finalReward > $offerwall->tier1_hold_amount) {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 0;
            }
            
        }

        //check if user has any upline
        $uplineId = User::where('id', $userId)->value('upline');
        if( $uplineId != 0 )
        {
            $upline = User::find($uplineId);
            $uplineCommision = abs($finalReward) / 100 * $refCommissionPercentage;
        } else
        {
            $uplineCommision = 0;
        }
        

        //if hold is enabled, calculate the hold time based on the reward
        if ( $offerwall->hold == 1 ) {
            if (  $finalReward >= $offerwall->tier1_hold_amount && $finalReward < $offerwall->tier2_hold_amount ) {
                $offerHoldTime = $offerwall->tier1_hold_time;
            }elseif ( $finalReward >= $offerwall->tier2_hold_amount && $finalReward < $offerwall->tier3_hold_amount ) {
                $offerHoldTime = $offerwall->tier2_hold_time;
            }
            else{
                $offerHoldTime = $offerwall->tier3_hold_time;
            }
        }else{ $offerHoldTime =0; }
        //check if transaction found in the database
        $transactionIdExists = OffersAndSurveysLog::where('transaction_id', $transactionId)->where('provider_name', $offerwall->name)->exists();

     /*===================================Create Log if complete, update if trx found and reversed ==========================================================*/
        //if transaction does not exist, create log
         if (!$transactionIdExists) 
         {
            OffersAndSurveysLog::create([
                'user_id' => $userId,
                'provider_name' => $offerwall->name,
                'payout' => $payout,
                'reward' => $finalReward,
                'added_expert_level' => $addToExpertLevel,
                'upline_commision' => $uplineCommision,
                'transaction_id' => $transactionId,
                'offer_name' => $offerName,
                'offer_id' => $offerId,
                'hold_time' => $offerHoldTime,
                'instant_credit' => 0,    // 0 no, 1 yes,
                'ip_address' => $ipAddress,
                'status' => $finalStatus,
            ]);

            /*===================================reward or deduct user and upline==========================================================*/
            //if finalstatus = 0(completed), reward, and if 2(reversed), deduct the worker and upline and corresponding log
            if ( $finalStatus == 0 ) 
            {
                $user->addWorkerBalance($finalReward);
                $user->increment('diamond_level_balance', $addToExpertLevel);
                $user->increment('total_earned', $finalReward);
                $user->increment('earned_from_offers', $finalReward);
                $user->increment('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' => 'reward ' . $finalReward . ' added from cpxsresearch',
                ]);
                if($uplineId != 0)
                {
                    $uplineToReward = User::find($uplineId);
                    $uplineToReward->addWorkerBalance( $uplineCommision );
                    $uplineToReward->increment('earned_from_referrals', $uplineCommision);
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'received referral Commission ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }
            elseif ( $finalStatus == 2 ) 
            {
                $user->deductWorkerBalance(abs($finalReward));
                $user->decrement('diamond_level_balance', abs($addToExpertLevel));
                $user->decrement('total_earned', abs($finalReward));
                $user->decrement('earned_from_offers', abs($finalReward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' =>  $finalReward . ' reversed by cpxsresearch',
                ]);
                if($uplineId != 0)
                {
                    $uplineToDeduct = User::find($uplineId);
                    $uplineToDeduct->deductWorkerBalance( abs($uplineCommision) );
                    $uplineToDeduct->decrement('earned_from_referrals', abs($uplineCommision));
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'deducted chargedback ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }

         } elseif ( $transactionIdExists && $finalStatus == 2 )
         {
            $offerIdToReverse = OffersAndSurveysLog::where('transaction_id', $transactionId)->value('id');
            $offer = OffersAndSurveysLog::find($offerIdToReverse);
            $userIdToReverse = $offer->user_id;
            $user = User::find($userIdToReverse);
            if( $offer->status == 0 )
            {
                $user->deductWorkerBalance(abs($offer->reward));
                $user->decrement('diamond_level_balance', abs($offer->reward));
                $user->decrement('total_earned', abs($offer->reward));
                $user->decrement('earned_from_offers', abs($offer->reward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $user->id,
                    'description' =>  $offer->reward . ' reversed by cpxsresearch',
                ]);
            }

            if($uplineId != 0)
            {
                $uplineToDeduct = User::find($uplineId);
                $uplineToDeduct->deductWorkerBalance( abs($offer->upline_commision) );
                $uplineToDeduct->decrement('earned_from_referrals', abs($offer->upline_commision));
                Log::create([
                    'user_id' => $uplineId, 
                    'description' => 'deducted chargedback ' . $offer->upline_commision . ' from user '. $user->username,
                ]);
            }
            $offer->update([ 'status' => 2 ]);
        }
         else
         {
            $user->deductWorkerBalance(abs($finalReward));
            $user->decrement('diamond_level_balance', abs($addToExpertLevel));
            $user->decrement('total_earned', abs($finalReward));
            $user->decrement('earned_from_offers', abs($finalReward));
            $user->decrement('total_offers_completed');
         }
    }



    public function lootably(Request $request)
    {
        $offerwall = Offerwall::where('name', 'Lootably')->first();
        /*===================================Ip Whitelist and Check Offerwall Enabled or not==================================*/
        $ipAddress = $request->ip();
        // Get the whitelisted IPs from the database
        $whitelistedIps = json_decode($offerwall->whitelisted_ips, true);

        // Check if $ipAddress is in the whitelisted IPs
        // if (in_array($ipAddress, $whitelistedIps)) {
            
        // } else {
        //     // IP address is not whitelisted, take appropriate action
        //     return "Access Denied! IP address $ipAddress is not whitelisted!";
        // }

        if ($offerwall->status != 1 ) {
            die();
            echo "offerwall is not enabled ";
        }
        /*===================================Get All common data from the postback==========================================================*/
        $uniqueUserId = $request->input('user_id');
        $payout = $request->input('payout');
        $currencyAmount = $request->input('reward');
        $transactionId = $request->input('transaction_id');
        $ipAddress = $request->has('ip') ? $request->input('ip') : null;
        $offerName = $request->has('offer_name') ? $request->input('offer_name') : null;
        $offerId = $request->has('offer_id') ? $request->input('offer_id') : null;
        $hash = $request->input('hash');
        
     /*===================================check the hash security==========================================================*/
        $secretKey = $offerwall->secret_key;
        $security = hash("sha256", $uniqueUserId . $ipAddress . $payout . $currencyAmount . $secretKey);
        if($security !== $hash){
            echo 0;
            die();
        }

     /*===================================Do necessary Calculations==========================================================*/


        $userId = User::where('unique_user_id', $uniqueUserId)->value('id');
        $user = User::find($userId);
        $refCommissionPercentage = $offerwall->ref_commission;
        $offerHold = $offerwall->hold;

        $userLevel = $user->level;
        $expertCp = $offerwall->expert_cp;
        if ( $userLevel == 0 ) 
        {
            $finalReward = ( $payout / 100 ) * $offerwall->starter_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 1 )
        {
            $finalReward = ( $payout / 100 ) * $offerwall->advance_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 2 ) {
            $finalReward = ( $payout / 100 ) * $offerwall->expert_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }else
        {
            Log::create([
                'user_id' => $userId, 
                'description' => 'user level is not specified',
            ]);
        }

        // if the transactionId starts with r- , it is a reverse transaction
        if (preg_match('/^r-(.*)/', $transactionId, $matches)) {
            // transactionId starts with "r-", $matches[1] contains the rest of the string
            $transactionId = $matches[1];
            $finalStatus = 0; //0=reversed
        }


        if( $request->has('status') ) 
        {
            if ($request->input('status') == 1 && $offerHold == 0 ) 
            {
                $finalStatus = 0; //0, completed, 1 on hold / pending, 2 reversed.
            }
            elseif ($request->input('status') == 1 && $finalReward <= $offerwall->tier1_hold_amount )
            {
                $finalStatus = 0;
            }
            elseif ( $request->input('status') == 1 && $offerHold == 1 && $offerwall->tier1_hold_amount < $finalReward && $currencyAmount > 0 && $payout > 0 )  //the complete status value of the provider offerwall
            {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 2;
            }
        }
        elseif ( $currencyAmount < 0 || $payout < 0)
        {
            $finalStatus = 2;
        }
        else
        {
            if ( $offerHold == 1 && $finalReward > $offerwall->tier1_hold_amount) {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 0;
            }
            
        }

        //check if user has any upline
        $uplineId = User::where('id', $userId)->value('upline');
        if( $uplineId != 0 )
        {
            $upline = User::find($uplineId);
            $uplineCommision = abs($finalReward) / 100 * $refCommissionPercentage;
        } else
        {
            $uplineCommision = 0;
        }
        

        //if hold is enabled, calculate the hold time based on the reward
        if ( $offerwall->hold == 1 ) {
            if (  $finalReward >= $offerwall->tier1_hold_amount && $finalReward < $offerwall->tier2_hold_amount ) {
                $offerHoldTime = $offerwall->tier1_hold_time;
            }elseif ( $finalReward >= $offerwall->tier2_hold_amount && $finalReward < $offerwall->tier3_hold_amount ) {
                $offerHoldTime = $offerwall->tier2_hold_time;
            }
            else{
                $offerHoldTime = $offerwall->tier3_hold_time;
            }
        }else{ $offerHoldTime =0; }
        //check if transaction found in the database
        $transactionIdExists = OffersAndSurveysLog::where('transaction_id', $transactionId)->where('provider_name', $offerwall->name)->exists();

     /*===================================Create Log if complete, update if trx found and reversed ==========================================================*/
        //if transaction does not exist, create log
         if (!$transactionIdExists) 
         {
            OffersAndSurveysLog::create([
                'user_id' => $userId,
                'provider_name' => $offerwall->name,
                'payout' => $payout,
                'reward' => $finalReward,
                'added_expert_level' => $addToExpertLevel,
                'upline_commision' => $uplineCommision,
                'transaction_id' => $transactionId,
                'offer_name' => $offerName,
                'offer_id' => $offerId,
                'hold_time' => $offerHoldTime,
                'instant_credit' => 0,    // 0 no, 1 yes,
                'ip_address' => $ipAddress,
                'status' => $finalStatus,
            ]);

            /*===================================reward or deduct user and upline==========================================================*/
            //if finalstatus = 0(completed), reward, and if 2(reversed), deduct the worker and upline and corresponding log
            if ( $finalStatus == 0 ) 
            {
                $user->addWorkerBalance($finalReward);
                $user->increment('diamond_level_balance', $addToExpertLevel);
                $user->increment('total_earned', $finalReward);
                $user->increment('earned_from_offers', $finalReward);
                $user->increment('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' => 'reward ' . $finalReward . ' added from lootably',
                ]);
                if($uplineId != 0)
                {
                    $uplineToReward = User::find($uplineId);
                    $uplineToReward->addWorkerBalance( $uplineCommision );
                    $uplineToReward->increment('earned_from_referrals', $uplineCommision);
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'received referral Commission ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }
            elseif ( $finalStatus == 2 ) 
            {
                $user->deductWorkerBalance(abs($finalReward));
                $user->decrement('diamond_level_balance', abs($addToExpertLevel));
                $user->decrement('total_earned', abs($finalReward));
                $user->decrement('earned_from_offers', abs($finalReward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' =>  $finalReward . ' reversed by lootably',
                ]);
                if($uplineId != 0)
                {
                    $uplineToDeduct = User::find($uplineId);
                    $uplineToDeduct->deductWorkerBalance( abs($uplineCommision) );
                    $uplineToDeduct->decrement('earned_from_referrals', abs($uplineCommision));
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'deducted chargedback ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }

         } elseif ( $transactionIdExists && $finalStatus == 2 )
         {
            $offerIdToReverse = OffersAndSurveysLog::where('transaction_id', $transactionId)->value('id');
            $offer = OffersAndSurveysLog::find($offerIdToReverse);
            $userIdToReverse = $offer->user_id;
            $user = User::find($userIdToReverse);
            if( $offer->status == 0 )
            {
                $user->deductWorkerBalance(abs($offer->reward));
                $user->decrement('diamond_level_balance', abs($offer->reward));
                $user->decrement('total_earned', abs($offer->reward));
                $user->decrement('earned_from_offers', abs($offer->reward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $user->id,
                    'description' =>  $offer->reward . ' reversed by lootably',
                ]);
            }

            if($uplineId != 0)
            {
                $uplineToDeduct = User::find($uplineId);
                $uplineToDeduct->deductWorkerBalance( abs($offer->upline_commision) );
                $uplineToDeduct->decrement('earned_from_referrals', abs($offer->upline_commision));
                Log::create([
                    'user_id' => $uplineId, 
                    'description' => 'deducted chargedback ' . $offer->upline_commision . ' from user '. $user->username,
                ]);
            }
            $offer->update([ 'status' => 2 ]);
        }
         else
         {
            $user->deductWorkerBalance(abs($finalReward));
            $user->decrement('diamond_level_balance', abs($addToExpertLevel));
            $user->decrement('total_earned', abs($finalReward));
            $user->decrement('earned_from_offers', abs($finalReward));
            $user->decrement('total_offers_completed');
         }
        echo 1;
        die();
    }



    public function offers4crypto(Request $request)
    {
        $offerwall = Offerwall::where('name', 'Offers4crypto')->first();
        /*===================================Ip Whitelist and Check Offerwall Enabled or not==================================*/
        $ipAddress = $request->ip();
        // Get the whitelisted IPs from the database
        $whitelistedIps = json_decode($offerwall->whitelisted_ips, true);

        // Check if $ipAddress is in the whitelisted IPs
        if (in_array($ipAddress, $whitelistedIps)) {
            
        } else {
            // IP address is not whitelisted, take appropriate action
            return "Access Denied! IP address $ipAddress is not whitelisted!";
        }

        if ($offerwall->status != 1 ) {
            die();
            echo "offerwall is not enabled ";
        }
        /*===================================Get All common data from the postback==========================================================*/
        $uniqueUserId = $request->input('subId');
        $payout = $request->input('payout');
        $currencyAmount = $request->input('reward');
        $transactionId = $request->input('transId');
        $ipAddress = $request->has('userIp') ? $request->input('userIp') : null;
        $offerName = $request->has('offer_name') ? $request->input('offer_name') : null;
        $offerId = $request->has('offer_id') ? $request->input('offer_id') : null;
        $hash = $request->input('signature');
        
         /*===================================check the hash security==========================================================*/
         $secretKey = $offerwall->secret_key;
         if(md5($uniqueUserId.$transactionId.$currencyAmount.$secretKey) != $hash)
         {
            echo "ERROR: Signature doesn't match";
             return;
         }

        /*===================================Do necessary Calculations==========================================================*/


        $userId = User::where('unique_user_id', $uniqueUserId)->value('id');
        $user = User::find($userId);
        $refCommissionPercentage = $offerwall->ref_commission;
        $offerHold = $offerwall->hold;

        $userLevel = $user->level;
        $expertCp = $offerwall->expert_cp;
        if ( $userLevel == 0 ) 
        {
            $finalReward = ( $payout / 100 ) * $offerwall->starter_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 1 )
        {
            $finalReward = ( $payout / 100 ) * $offerwall->advance_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 2 ) {
            $finalReward = ( $payout / 100 ) * $offerwall->expert_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }else
        {
            Log::create([
                'user_id' => $userId, 
                'description' => 'user level is not specified',
            ]);
        }

        // if the transactionId starts with r- , it is a reverse transaction
        if (preg_match('/^r-(.*)/', $transactionId, $matches)) {
            // transactionId starts with "r-", $matches[1] contains the rest of the string
            $transactionId = $matches[1];
            $finalStatus = 0; //0=reversed
        }


        if( $request->has('status') ) 
        {
            if ($request->input('status') == 1 && $offerHold == 0 ) 
            {
                $finalStatus = 0; //0, completed, 1 on hold / pending, 2 reversed.
            }
            elseif ($request->input('status') == 1 && $finalReward <= $offerwall->tier1_hold_amount )
            {
                $finalStatus = 0;
            }
            elseif ( $request->input('status') == 1 && $offerHold == 1 && $offerwall->tier1_hold_amount < $finalReward && $currencyAmount > 0 && $payout > 0 )  //the complete status value of the provider offerwall
            {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 2;
            }
        }
        elseif ( $currencyAmount < 0 || $payout < 0)
        {
            $finalStatus = 2;
        }
        else
        {
            if ( $offerHold == 1 && $finalReward > $offerwall->tier1_hold_amount) {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 0;
            }
            
        }

        //check if user has any upline
        $uplineId = User::where('id', $userId)->value('upline');
        if( $uplineId != 0 )
        {
            $upline = User::find($uplineId);
            $uplineCommision = abs($finalReward) / 100 * $refCommissionPercentage;
        } else
        {
            $uplineCommision = 0;
        }
        

        //if hold is enabled, calculate the hold time based on the reward
        if ( $offerwall->hold == 1 ) {
            if (  $finalReward >= $offerwall->tier1_hold_amount && $finalReward < $offerwall->tier2_hold_amount ) {
                $offerHoldTime = $offerwall->tier1_hold_time;
            }elseif ( $finalReward >= $offerwall->tier2_hold_amount && $finalReward < $offerwall->tier3_hold_amount ) {
                $offerHoldTime = $offerwall->tier2_hold_time;
            }
            else{
                $offerHoldTime = $offerwall->tier3_hold_time;
            }
        }else{ $offerHoldTime =0; }
        //check if transaction found in the database
        $transactionIdExists = OffersAndSurveysLog::where('transaction_id', $transactionId)->where('provider_name', $offerwall->name)->exists();

        /*===================================Create Log if complete, update if trx found and reversed ==========================================================*/
        //if transaction does not exist, create log
         if (!$transactionIdExists) 
         {
            OffersAndSurveysLog::create([
                'user_id' => $userId,
                'provider_name' => $offerwall->name,
                'payout' => $payout,
                'reward' => $finalReward,
                'added_expert_level' => $addToExpertLevel,
                'upline_commision' => $uplineCommision,
                'transaction_id' => $transactionId,
                'offer_name' => $offerName,
                'offer_id' => $offerId,
                'hold_time' => $offerHoldTime,
                'instant_credit' => 0,    // 0 no, 1 yes,
                'ip_address' => $ipAddress,
                'status' => $finalStatus,
            ]);

            /*===================================reward or deduct user and upline==========================================================*/
            //if finalstatus = 0(completed), reward, and if 2(reversed), deduct the worker and upline and corresponding log
            if ( $finalStatus == 0 ) 
            {
                $user->addWorkerBalance($finalReward);
                $user->increment('diamond_level_balance', $addToExpertLevel);
                $user->increment('total_earned', $finalReward);
                $user->increment('earned_from_offers', $finalReward);
                $user->increment('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' => 'reward ' . $finalReward . ' added from offers4crypto',
                ]);
                if($uplineId != 0)
                {
                    $uplineToReward = User::find($uplineId);
                    $uplineToReward->addWorkerBalance( $uplineCommision );
                    $uplineToReward->increment('earned_from_referrals', $uplineCommision);
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'received referral Commission ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }
            elseif ( $finalStatus == 2 ) 
            {
                $user->deductWorkerBalance(abs($finalReward));
                $user->decrement('diamond_level_balance', abs($addToExpertLevel));
                $user->decrement('total_earned', abs($finalReward));
                $user->decrement('earned_from_offers', abs($finalReward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' =>  $finalReward . ' reversed by offers4crypto',
                ]);
                if($uplineId != 0)
                {
                    $uplineToDeduct = User::find($uplineId);
                    $uplineToDeduct->deductWorkerBalance( abs($uplineCommision) );
                    $uplineToDeduct->decrement('earned_from_referrals', abs($uplineCommision));
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'deducted chargedback ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }

         } elseif ( $transactionIdExists && $finalStatus == 2 )
         {
            $offerIdToReverse = OffersAndSurveysLog::where('transaction_id', $transactionId)->value('id');
            $offer = OffersAndSurveysLog::find($offerIdToReverse);
            $userIdToReverse = $offer->user_id;
            $user = User::find($userIdToReverse);
            if( $offer->status == 0 )
            {
                $user->deductWorkerBalance(abs($offer->reward));
                $user->decrement('diamond_level_balance', abs($offer->reward));
                $user->decrement('total_earned', abs($offer->reward));
                $user->decrement('earned_from_offers', abs($offer->reward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $user->id,
                    'description' =>  $offer->reward . ' reversed by offers4crypto',
                ]);
            }

            if($uplineId != 0)
            {
                $uplineToDeduct = User::find($uplineId);
                $uplineToDeduct->deductWorkerBalance( abs($offer->upline_commision) );
                $uplineToDeduct->decrement('earned_from_referrals', abs($offer->upline_commision));
                Log::create([
                    'user_id' => $uplineId, 
                    'description' => 'deducted chargedback ' . $offer->upline_commision . ' from user '. $user->username,
                ]);
            }
            $offer->update([ 'status' => 2 ]);
        }
         else
         {
            $user->deductWorkerBalance(abs($finalReward));
            $user->decrement('diamond_level_balance', abs($addToExpertLevel));
            $user->decrement('total_earned', abs($finalReward));
            $user->decrement('earned_from_offers', abs($finalReward));
            $user->decrement('total_offers_completed');
         }
         echo 1;
         die();
    }



    public function excentiv(Request $request)
    {
        $offerwall = Offerwall::where('name', 'Excentiv')->first();
        /*===================================Ip Whitelist and Check Offerwall Enabled or not==================================*/ 
        $ipAddress = $request->ip();
        // Get the whitelisted IPs from the database
        $whitelistedIps = json_decode($offerwall->whitelisted_ips, true);

        // Check if $ipAddress is in the whitelisted IPs
        if (in_array($ipAddress, $whitelistedIps)) {
            
        } else {
            // IP address is not whitelisted, take appropriate action
            return "Access Denied! IP address $ipAddress is not whitelisted!";
        }

        if ($offerwall->status != 1 ) {
            die();
            echo "offerwall is not enabled ";
        }
        /*===================================Get All common data from the postback==========================================================*/
        $uniqueUserId = $request->input('user_id');
        $payout = $request->input('payout');
        $currencyAmount = $request->input('reward');
        $transactionId = $request->input('transaction_id');
        $ipAddress = $request->has('ip') ? $request->input('ip') : null;
        $offerName = $request->has('offer_name') ? $request->input('offer_name') : null;
        $offerId = $request->has('offer_id') ? $request->input('offer_id') : null;
        $hash = $request->input('hash');
        
     /*===================================check the hash security==========================================================*/
     if(md5($uniqueUserId.$transactionId.$currencyAmount.$offerwall->secret_key) != $hash)
     {
      echo "ERROR: Signature doesn't match";
      return;
     }

     /*===================================Do necessary Calculations==========================================================*/


        $userId = User::where('unique_user_id', $uniqueUserId)->value('id');
        $user = User::find($userId);
        $refCommissionPercentage = $offerwall->ref_commission;
        $offerHold = $offerwall->hold;

        $userLevel = $user->level;
        $expertCp = $offerwall->expert_cp;
        if ( $userLevel == 0 ) 
        {
            $finalReward = ( $payout / 100 ) * $offerwall->starter_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 1 )
        {
            $finalReward = ( $payout / 100 ) * $offerwall->advance_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 2 ) {
            $finalReward = ( $payout / 100 ) * $offerwall->expert_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }else
        {
            Log::create([
                'user_id' => $userId, 
                'description' => 'user level is not specified',
            ]);
        }

        // if the transactionId starts with r- , it is a reverse transaction
        if (preg_match('/^r-(.*)/', $transactionId, $matches)) {
            // transactionId starts with "r-", $matches[1] contains the rest of the string
            $transactionId = $matches[1];
            $finalStatus = 0; //0=reversed
        }


        if( $request->has('status') ) 
        {
            if ($request->input('status') == 1 && $offerHold == 0 ) 
            {
                $finalStatus = 0; //0, completed, 1 on hold / pending, 2 reversed.
            }
            elseif ($request->input('status') == 1 && $finalReward <= $offerwall->tier1_hold_amount )
            {
                $finalStatus = 0;
            }
            elseif ( $request->input('status') == 1 && $offerHold == 1 && $offerwall->tier1_hold_amount < $finalReward && $currencyAmount > 0 && $payout > 0 )  //the complete status value of the provider offerwall
            {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 2;
            }
        }
        elseif ( $currencyAmount < 0 || $payout < 0)
        {
            $finalStatus = 2;
        }
        else
        {
            if ( $offerHold == 1 && $finalReward > $offerwall->tier1_hold_amount) {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 0;
            }
            
        }

        //check if user has any upline
        $uplineId = User::where('id', $userId)->value('upline');
        if( $uplineId != 0 )
        {
            $upline = User::find($uplineId);
            $uplineCommision = abs($finalReward) / 100 * $refCommissionPercentage;
        } else
        {
            $uplineCommision = 0;
        }
        

        //if hold is enabled, calculate the hold time based on the reward
        if ( $offerwall->hold == 1 ) {
            if (  $finalReward >= $offerwall->tier1_hold_amount && $finalReward < $offerwall->tier2_hold_amount ) {
                $offerHoldTime = $offerwall->tier1_hold_time;
            }elseif ( $finalReward >= $offerwall->tier2_hold_amount && $finalReward < $offerwall->tier3_hold_amount ) {
                $offerHoldTime = $offerwall->tier2_hold_time;
            }
            else{
                $offerHoldTime = $offerwall->tier3_hold_time;
            }
        }else{ $offerHoldTime =0; }
        //check if transaction found in the database
        $transactionIdExists = OffersAndSurveysLog::where('transaction_id', $transactionId)->where('provider_name', $offerwall->name)->exists();

     /*===================================Create Log if complete, update if trx found and reversed ==========================================================*/
        //if transaction does not exist, create log
         if (!$transactionIdExists) 
         {
            OffersAndSurveysLog::create([
                'user_id' => $userId,
                'provider_name' => $offerwall->name,
                'payout' => $payout,
                'reward' => $finalReward,
                'added_expert_level' => $addToExpertLevel,
                'upline_commision' => $uplineCommision,
                'transaction_id' => $transactionId,
                'offer_name' => $offerName,
                'offer_id' => $offerId,
                'hold_time' => $offerHoldTime,
                'instant_credit' => 0,    // 0 no, 1 yes,
                'ip_address' => $ipAddress,
                'status' => $finalStatus,
            ]);

            /*===================================reward or deduct user and upline==========================================================*/
            //if finalstatus = 0(completed), reward, and if 2(reversed), deduct the worker and upline and corresponding log
            if ( $finalStatus == 0 ) 
            {
                $user->addWorkerBalance($finalReward);
                $user->increment('diamond_level_balance', $addToExpertLevel);
                $user->increment('total_earned', $finalReward);
                $user->increment('earned_from_offers', $finalReward);
                $user->increment('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' => 'reward ' . $finalReward . ' added from excentiv',
                ]);
                if($uplineId != 0)
                {
                    $uplineToReward = User::find($uplineId);
                    $uplineToReward->addWorkerBalance( $uplineCommision );
                    $uplineToReward->increment('earned_from_referrals', $uplineCommision);
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'received referral Commission ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }
            elseif ( $finalStatus == 2 ) 
            {
                $user->deductWorkerBalance(abs($finalReward));
                $user->decrement('diamond_level_balance', abs($addToExpertLevel));
                $user->decrement('total_earned', abs($finalReward));
                $user->decrement('earned_from_offers', abs($finalReward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' =>  $finalReward . ' reversed by excentiv',
                ]);
                if($uplineId != 0)
                {
                    $uplineToDeduct = User::find($uplineId);
                    $uplineToDeduct->deductWorkerBalance( abs($uplineCommision) );
                    $uplineToDeduct->decrement('earned_from_referrals', abs($uplineCommision));
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'deducted chargedback ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }

         } elseif ( $transactionIdExists && $finalStatus == 2 )
         {
            $offerIdToReverse = OffersAndSurveysLog::where('transaction_id', $transactionId)->value('id');
            $offer = OffersAndSurveysLog::find($offerIdToReverse);
            $userIdToReverse = $offer->user_id;
            $user = User::find($userIdToReverse);
            if( $offer->status == 0 )
            {
                $user->deductWorkerBalance(abs($offer->reward));
                $user->decrement('diamond_level_balance', abs($offer->reward));
                $user->decrement('total_earned', abs($offer->reward));
                $user->decrement('earned_from_offers', abs($offer->reward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $user->id,
                    'description' =>  $offer->reward . ' reversed by excentiv',
                ]);
            }

            if($uplineId != 0)
            {
                $uplineToDeduct = User::find($uplineId);
                $uplineToDeduct->deductWorkerBalance( abs($offer->upline_commision) );
                $uplineToDeduct->decrement('earned_from_referrals', abs($offer->upline_commision));
                Log::create([
                    'user_id' => $uplineId, 
                    'description' => 'deducted chargedback ' . $offer->upline_commision . ' from user '. $user->username,
                ]);
            }
            $offer->update([ 'status' => 2 ]);
        }
         else
         {
            $user->deductWorkerBalance(abs($finalReward));
            $user->decrement('diamond_level_balance', abs($addToExpertLevel));
            $user->decrement('total_earned', abs($finalReward));
            $user->decrement('earned_from_offers', abs($finalReward));
            $user->decrement('total_offers_completed');
         }
    }



    public function kiwiwall(Request $request)
    {
        $offerwall = Offerwall::where('name', 'Kiwiwall')->first();
        /*===================================Ip Whitelist and Check Offerwall Enabled or not==================================*/
        $ipAddress = $request->ip();
        // Get the whitelisted IPs from the database
        $whitelistedIps = json_decode($offerwall->whitelisted_ips, true);

        // Check if $ipAddress is in the whitelisted IPs
        if (in_array($ipAddress, $whitelistedIps)) {
            
        } else {
            // IP address is not whitelisted, take appropriate action
            return "Access Denied! IP address $ipAddress is not whitelisted!";
        }

        if ($offerwall->status != 1 ) {
            die();
            echo "offerwall is not enabled ";
        }
        /*===================================Get All common data from the postback==========================================================*/
        $uniqueUserId = $request->input('sub_id');
        $payout = $request->input('gross');
        $currencyAmount = $request->input('amount');
        $transactionId = $request->input('trans_id');
        $ipAddress = $request->has('ip_address') ? $request->input('ip_address') : null;
        $offerName = $request->has('offer_name') ? $request->input('offer_name') : null;
        $offerId = $request->has('offer_id') ? $request->input('offer_id') : null;
        $hash = $request->input('signature');
        
         /*===================================check the hash security==========================================================*/
         $secret_key = $offerwall->secret_key;
         $validation_signature = md5($uniqueUserId . ':' . $currencyAmount . ':' . $secret_key);
         if ($hash != $validation_signature) {
            // Signatures not equal - send error code
            echo 0;
            die();
        }

        /*===================================Do necessary Calculations==========================================================*/


        $userId = User::where('unique_user_id', $uniqueUserId)->value('id');
        $user = User::find($userId);
        $refCommissionPercentage = $offerwall->ref_commission;
        $offerHold = $offerwall->hold;

        $userLevel = $user->level;
        $expertCp = $offerwall->expert_cp;
        if ( $userLevel == 0 ) 
        {
            $finalReward = ( $payout / 100 ) * $offerwall->starter_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 1 )
        {
            $finalReward = ( $payout / 100 ) * $offerwall->advance_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 2 ) {
            $finalReward = ( $payout / 100 ) * $offerwall->expert_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }else
        {
            Log::create([
                'user_id' => $userId, 
                'description' => 'user level is not specified',
            ]);
        }

        // if the transactionId starts with r- , it is a reverse transaction
        if (preg_match('/^r-(.*)/', $transactionId, $matches)) {
            // transactionId starts with "r-", $matches[1] contains the rest of the string
            $transactionId = $matches[1];
            $finalStatus = 0; //0=reversed
        }


        if( $request->has('status') ) 
        {
            if ($request->input('status') == 1 && $offerHold == 0 ) 
            {
                $finalStatus = 0; //0, completed, 1 on hold / pending, 2 reversed.
            }
            elseif ($request->input('status') == 1 && $finalReward <= $offerwall->tier1_hold_amount )
            {
                $finalStatus = 0;
            }
            elseif ( $request->input('status') == 1 && $offerHold == 1 && $offerwall->tier1_hold_amount < $finalReward && $currencyAmount > 0 && $payout > 0 )  //the complete status value of the provider offerwall
            {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 2;
            }
        }
        elseif ( $currencyAmount < 0 || $payout < 0)
        {
            $finalStatus = 2;
        }
        else
        {
            if ( $offerHold == 1 && $finalReward > $offerwall->tier1_hold_amount) {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 0;
            }
            
        }

        //check if user has any upline
        $uplineId = User::where('id', $userId)->value('upline');
        if( $uplineId != 0 )
        {
            $upline = User::find($uplineId);
            $uplineCommision = abs($finalReward) / 100 * $refCommissionPercentage;
        } else
        {
            $uplineCommision = 0;
        }
        

        //if hold is enabled, calculate the hold time based on the reward
        if ( $offerwall->hold == 1 ) {
            if (  $finalReward >= $offerwall->tier1_hold_amount && $finalReward < $offerwall->tier2_hold_amount ) {
                $offerHoldTime = $offerwall->tier1_hold_time;
            }elseif ( $finalReward >= $offerwall->tier2_hold_amount && $finalReward < $offerwall->tier3_hold_amount ) {
                $offerHoldTime = $offerwall->tier2_hold_time;
            }
            else{
                $offerHoldTime = $offerwall->tier3_hold_time;
            }
        }else{ $offerHoldTime =0; }
        //check if transaction found in the database
        $transactionIdExists = OffersAndSurveysLog::where('transaction_id', $transactionId)->where('provider_name', $offerwall->name)->exists();

        /*===================================Create Log if complete, update if trx found and reversed ==========================================================*/
        //if transaction does not exist, create log
         if (!$transactionIdExists) 
         {
            OffersAndSurveysLog::create([
                'user_id' => $userId,
                'provider_name' => $offerwall->name,
                'payout' => $payout,
                'reward' => $finalReward,
                'added_expert_level' => $addToExpertLevel,
                'upline_commision' => $uplineCommision,
                'transaction_id' => $transactionId,
                'offer_name' => $offerName,
                'offer_id' => $offerId,
                'hold_time' => $offerHoldTime,
                'instant_credit' => 0,    // 0 no, 1 yes,
                'ip_address' => $ipAddress,
                'status' => $finalStatus,
            ]);

            /*===================================reward or deduct user and upline==========================================================*/
            //if finalstatus = 0(completed), reward, and if 2(reversed), deduct the worker and upline and corresponding log
            if ( $finalStatus == 0 ) 
            {
                $user->addWorkerBalance($finalReward);
                $user->increment('diamond_level_balance', $addToExpertLevel);
                $user->increment('total_earned', $finalReward);
                $user->increment('earned_from_offers', $finalReward);
                $user->increment('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' => 'reward ' . $finalReward . ' added from kiwiwall',
                ]);
                if($uplineId != 0)
                {
                    $uplineToReward = User::find($uplineId);
                    $uplineToReward->addWorkerBalance( $uplineCommision );
                    $uplineToReward->increment('earned_from_referrals', $uplineCommision);
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'received referral Commission ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }
            elseif ( $finalStatus == 2 ) 
            {
                $user->deductWorkerBalance(abs($finalReward));
                $user->decrement('diamond_level_balance', abs($addToExpertLevel));
                $user->decrement('total_earned', abs($finalReward));
                $user->decrement('earned_from_offers', abs($finalReward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' =>  $finalReward . ' reversed by kiwiwall',
                ]);
                if($uplineId != 0)
                {
                    $uplineToDeduct = User::find($uplineId);
                    $uplineToDeduct->deductWorkerBalance( abs($uplineCommision) );
                    $uplineToDeduct->decrement('earned_from_referrals', abs($uplineCommision));
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'deducted chargedback ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }

         } elseif ( $transactionIdExists && $finalStatus == 2 )
         {
            $offerIdToReverse = OffersAndSurveysLog::where('transaction_id', $transactionId)->value('id');
            $offer = OffersAndSurveysLog::find($offerIdToReverse);
            $userIdToReverse = $offer->user_id;
            $user = User::find($userIdToReverse);
            if( $offer->status == 0 )
            {
                $user->deductWorkerBalance(abs($offer->reward));
                $user->decrement('diamond_level_balance', abs($offer->reward));
                $user->decrement('total_earned', abs($offer->reward));
                $user->decrement('earned_from_offers', abs($offer->reward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $user->id,
                    'description' =>  $offer->reward . ' reversed by kiwiwall',
                ]);
            }

            if($uplineId != 0)
            {
                $uplineToDeduct = User::find($uplineId);
                $uplineToDeduct->deductWorkerBalance( abs($offer->upline_commision) );
                $uplineToDeduct->decrement('earned_from_referrals', abs($offer->upline_commision));
                Log::create([
                    'user_id' => $uplineId, 
                    'description' => 'deducted chargedback ' . $offer->upline_commision . ' from user '. $user->username,
                ]);
            }
            $offer->update([ 'status' => 2 ]);
        }
         else
         {
            $user->deductWorkerBalance(abs($finalReward));
            $user->decrement('diamond_level_balance', abs($addToExpertLevel));
            $user->decrement('total_earned', abs($finalReward));
            $user->decrement('earned_from_offers', abs($finalReward));
            $user->decrement('total_offers_completed');
         }
    }



    public function monlix(Request $request)
    {
        $offerwall = Offerwall::where('name', 'Monlix')->first();
        /*===================================Ip Whitelist and Check Offerwall Enabled or not==================================*/
        $ipAddress = $request->ip();
        // Get the whitelisted IPs from the database
        $whitelistedIps = json_decode($offerwall->whitelisted_ips, true);

        // Check if $ipAddress is in the whitelisted IPs
        // if (in_array($ipAddress, $whitelistedIps)) {
            
        // } else {
        //     // IP address is not whitelisted, take appropriate action
        //     return "Access Denied! IP address $ipAddress is not whitelisted!";
        // }

        if ($offerwall->status != 1 ) {
            die();
            echo "offerwall is not enabled ";
        }
        /*===================================Get All common data from the postback==========================================================*/
        $uniqueUserId = $request->input('user_id');
        $payout = $request->input('payout');
        $currencyAmount = $request->input('reward');
        $transactionId = $request->input('transaction_id');
        $ipAddress = $request->has('ip') ? $request->input('ip') : null;
        $offerName = $request->has('offer_name') ? $request->input('offer_name') : null;
        $offerId = $request->has('offer_id') ? $request->input('offer_id') : null;
        $hash = $request->input('hash');
        
     /*===================================check the hash security==========================================================*/
        if ($hash != $offerwall->secret_key) {
           echo "ERROR: Signature doesn't match";
           return;
        }
        

     /*===================================Do necessary Calculations==========================================================*/


        $userId = User::where('unique_user_id', $uniqueUserId)->value('id');
        $user = User::find($userId);
        $refCommissionPercentage = $offerwall->ref_commission;
        $offerHold = $offerwall->hold;

        $userLevel = $user->level;
        $expertCp = $offerwall->expert_cp;
        if ( $userLevel == 0 ) 
        {
            $finalReward = ( $payout / 100 ) * $offerwall->starter_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 1 )
        {
            $finalReward = ( $payout / 100 ) * $offerwall->advance_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 2 ) {
            $finalReward = ( $payout / 100 ) * $offerwall->expert_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }else
        {
            Log::create([
                'user_id' => $userId, 
                'description' => 'user level is not specified',
            ]);
        }

        // if the transactionId starts with r- , it is a reverse transaction
        if (preg_match('/^r-(.*)/', $transactionId, $matches)) {
            // transactionId starts with "r-", $matches[1] contains the rest of the string
            $transactionId = $matches[1];
            $finalStatus = 0; //0=reversed
        }


        if( $request->has('status') ) 
        {
            if ($request->input('status') == 1 && $offerHold == 0 ) 
            {
                $finalStatus = 0; //0, completed, 1 on hold / pending, 2 reversed.
            }
            elseif ($request->input('status') == 1 && $finalReward <= $offerwall->tier1_hold_amount )
            {
                $finalStatus = 0;
            }
            elseif ( $request->input('status') == 1 && $offerHold == 1 && $offerwall->tier1_hold_amount < $finalReward && $currencyAmount > 0 && $payout > 0 )  //the complete status value of the provider offerwall
            {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 2;
            }
        }
        elseif ( $currencyAmount < 0 || $payout < 0)
        {
            $finalStatus = 2;
        }
        else
        {
            if ( $offerHold == 1 && $finalReward > $offerwall->tier1_hold_amount) {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 0;
            }
            
        }

        //check if user has any upline
        $uplineId = User::where('id', $userId)->value('upline');
        if( $uplineId != 0 )
        {
            $upline = User::find($uplineId);
            $uplineCommision = abs($finalReward) / 100 * $refCommissionPercentage;
        } else
        {
            $uplineCommision = 0;
        }
        

        //if hold is enabled, calculate the hold time based on the reward
        if ( $offerwall->hold == 1 ) {
            if (  $finalReward >= $offerwall->tier1_hold_amount && $finalReward < $offerwall->tier2_hold_amount ) {
                $offerHoldTime = $offerwall->tier1_hold_time;
            }elseif ( $finalReward >= $offerwall->tier2_hold_amount && $finalReward < $offerwall->tier3_hold_amount ) {
                $offerHoldTime = $offerwall->tier2_hold_time;
            }
            else{
                $offerHoldTime = $offerwall->tier3_hold_time;
            }
        }else{ $offerHoldTime =0; }
        //check if transaction found in the database
        $transactionIdExists = OffersAndSurveysLog::where('transaction_id', $transactionId)->where('provider_name', $offerwall->name)->exists();

     /*===================================Create Log if complete, update if trx found and reversed ==========================================================*/
        //if transaction does not exist, create log
         if (!$transactionIdExists) 
         {
            OffersAndSurveysLog::create([
                'user_id' => $userId,
                'provider_name' => $offerwall->name,
                'payout' => $payout,
                'reward' => $finalReward,
                'added_expert_level' => $addToExpertLevel,
                'upline_commision' => $uplineCommision,
                'transaction_id' => $transactionId,
                'offer_name' => $offerName,
                'offer_id' => $offerId,
                'hold_time' => $offerHoldTime,
                'instant_credit' => 0,    // 0 no, 1 yes,
                'ip_address' => $ipAddress,
                'status' => $finalStatus,
            ]);

            /*===================================reward or deduct user and upline==========================================================*/
            //if finalstatus = 0(completed), reward, and if 2(reversed), deduct the worker and upline and corresponding log
            if ( $finalStatus == 0 ) 
            {
                $user->addWorkerBalance($finalReward);
                $user->increment('diamond_level_balance', $addToExpertLevel);
                $user->increment('total_earned', $finalReward);
                $user->increment('earned_from_offers', $finalReward);
                $user->increment('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' => 'reward ' . $finalReward . ' added from monlix',
                ]);
                if($uplineId != 0)
                {
                    $uplineToReward = User::find($uplineId);
                    $uplineToReward->addWorkerBalance( $uplineCommision );
                    $uplineToReward->increment('earned_from_referrals', $uplineCommision);
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'received referral Commission ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }
            elseif ( $finalStatus == 2 ) 
            {
                $user->deductWorkerBalance(abs($finalReward));
                $user->decrement('diamond_level_balance', abs($addToExpertLevel));
                $user->decrement('total_earned', abs($finalReward));
                $user->decrement('earned_from_offers', abs($finalReward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' =>  $finalReward . ' reversed by monlix',
                ]);
                if($uplineId != 0)
                {
                    $uplineToDeduct = User::find($uplineId);
                    $uplineToDeduct->deductWorkerBalance( abs($uplineCommision) );
                    $uplineToDeduct->decrement('earned_from_referrals', abs($uplineCommision));
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'deducted chargedback ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }

         } elseif ( $transactionIdExists && $finalStatus == 2 )
         {
            $offerIdToReverse = OffersAndSurveysLog::where('transaction_id', $transactionId)->value('id');
            $offer = OffersAndSurveysLog::find($offerIdToReverse);
            $userIdToReverse = $offer->user_id;
            $user = User::find($userIdToReverse);
            if( $offer->status == 0 )
            {
                $user->deductWorkerBalance(abs($offer->reward));
                $user->decrement('diamond_level_balance', abs($offer->reward));
                $user->decrement('total_earned', abs($offer->reward));
                $user->decrement('earned_from_offers', abs($offer->reward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $user->id,
                    'description' =>  $offer->reward . ' reversed by monlix',
                ]);
            }

            if($uplineId != 0)
            {
                $uplineToDeduct = User::find($uplineId);
                $uplineToDeduct->deductWorkerBalance( abs($offer->upline_commision) );
                $uplineToDeduct->decrement('earned_from_referrals', abs($offer->upline_commision));
                Log::create([
                    'user_id' => $uplineId, 
                    'description' => 'deducted chargedback ' . $offer->upline_commision . ' from user '. $user->username,
                ]);
            }
            $offer->update([ 'status' => 2 ]);
        }
         else
         {
            $user->deductWorkerBalance(abs($finalReward));
            $user->decrement('diamond_level_balance', abs($addToExpertLevel));
            $user->decrement('total_earned', abs($finalReward));
            $user->decrement('earned_from_offers', abs($finalReward));
            $user->decrement('total_offers_completed');
         }

         echo "OK";
    }



    public function notik(Request $request)
    {
        $offerwall = Offerwall::where('name', 'Notik')->first();
        /*===================================Ip Whitelist and Check Offerwall Enabled or not==================================*/
        $ipAddress = $request->ip();
        // Get the whitelisted IPs from the database
        $whitelistedIps = json_decode($offerwall->whitelisted_ips, true);

        // Check if $ipAddress is in the whitelisted IPs
        if (in_array($ipAddress, $whitelistedIps)) {
            
        } else {
            // IP address is not whitelisted, take appropriate action
            return "Access Denied! IP address $ipAddress is not whitelisted!";
        }

        if ($offerwall->status != 1 ) {
            die();
            echo "offerwall is not enabled ";
        }
        /*===================================Get All common data from the postback==========================================================*/
        $uniqueUserId = $request->input('user_id');
        $payout = $request->input('payout');
        $currencyAmount = $request->input('amount');
        $transactionId = $request->input('txn_id');
        $ipAddress = $request->has('conversion_ip') ? $request->input('conversion_ip') : null;
        $offerName = $request->has('offer_name') ? $request->input('offer_name') : null;
        $offerId = $request->has('offer_id') ? $request->input('offer_id') : null;
        $hash = $request->input('hash');
        
     /*===================================check the hash security==========================================================*/
        /*Create validation hash and validate hashes*/
        $secretKey = $offerwall->secret_key; // This has to be your App's secret key that you can find in you App detail page
        /*Get the currently active http protocol*/
        $protocol = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") ? "https" : "http";
        /*Build the full callback URL*/
        /*Example: https://url.com?param1=foo&param2=bar&hash=3171f6b78e06cadcec4c9c3b15f8588400e8738*/
        $url = "$protocol://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        /*Get the callback URL without the "hash" query parameter*/
        /*Example: https://url.com?param1=foo&param2=bar*/
        $urlWithoutHash = substr($url, 0, -strlen("&hash=$hash"));
        /*Generate a hash from the complete callback URL without the "hash" query parameter*/
        $generatedHash = hash_hmac("sha1", $urlWithoutHash, $secretKey);

        /*Check if the generated hash is the same as the "hash" query parameter*/
        if ($generatedHash !== $hash) {
            echo 0;
            die();
        }

        /*===================================Do necessary Calculations==========================================================*/


        $userId = User::where('unique_user_id', $uniqueUserId)->value('id');
        $user = User::find($userId);
        $refCommissionPercentage = $offerwall->ref_commission;
        $offerHold = $offerwall->hold;

        $userLevel = $user->level;
        $expertCp = $offerwall->expert_cp;
        if ( $userLevel == 0 ) 
        {
            $finalReward = ( $payout / 100 ) * $offerwall->starter_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 1 )
        {
            $finalReward = ( $payout / 100 ) * $offerwall->advance_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 2 ) {
            $finalReward = ( $payout / 100 ) * $offerwall->expert_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }else
        {
            Log::create([
                'user_id' => $userId, 
                'description' => 'user level is not specified',
            ]);
        }

        // if the transactionId starts with r- , it is a reverse transaction
        if (preg_match('/^r-(.*)/', $transactionId, $matches)) {
            // transactionId starts with "r-", $matches[1] contains the rest of the string
            $transactionId = $matches[1];
            $finalStatus = 0; //0=reversed
        }


        if( $request->has('status') ) 
        {
            if ($request->input('status') == 1 && $offerHold == 0 ) 
            {
                $finalStatus = 0; //0, completed, 1 on hold / pending, 2 reversed.
            }
            elseif ($request->input('status') == 1 && $finalReward <= $offerwall->tier1_hold_amount )
            {
                $finalStatus = 0;
            }
            elseif ( $request->input('status') == 1 && $offerHold == 1 && $offerwall->tier1_hold_amount < $finalReward && $currencyAmount > 0 && $payout > 0 )  //the complete status value of the provider offerwall
            {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 2;
            }
        }
        elseif ( $currencyAmount < 0 || $payout < 0)
        {
            $finalStatus = 2;
        }
        else
        {
            if ( $offerHold == 1 && $finalReward > $offerwall->tier1_hold_amount) {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 0;
            }
            
        }

        //check if user has any upline
        $uplineId = User::where('id', $userId)->value('upline');
        if( $uplineId != 0 )
        {
            $upline = User::find($uplineId);
            $uplineCommision = abs($finalReward) / 100 * $refCommissionPercentage;
        } else
        {
            $uplineCommision = 0;
        }
        

        //if hold is enabled, calculate the hold time based on the reward
        if ( $offerwall->hold == 1 ) {
            if (  $finalReward >= $offerwall->tier1_hold_amount && $finalReward < $offerwall->tier2_hold_amount ) {
                $offerHoldTime = $offerwall->tier1_hold_time;
            }elseif ( $finalReward >= $offerwall->tier2_hold_amount && $finalReward < $offerwall->tier3_hold_amount ) {
                $offerHoldTime = $offerwall->tier2_hold_time;
            }
            else{
                $offerHoldTime = $offerwall->tier3_hold_time;
            }
        }else{ $offerHoldTime =0; }
        //check if transaction found in the database
        $transactionIdExists = OffersAndSurveysLog::where('transaction_id', $transactionId)->where('provider_name', $offerwall->name)->exists();

        /*===================================Create Log if complete, update if trx found and reversed ==========================================================*/
        //if transaction does not exist, create log
         if (!$transactionIdExists) 
         {
            OffersAndSurveysLog::create([
                'user_id' => $userId,
                'provider_name' => $offerwall->name,
                'payout' => $payout,
                'reward' => $finalReward,
                'added_expert_level' => $addToExpertLevel,
                'upline_commision' => $uplineCommision,
                'transaction_id' => $transactionId,
                'offer_name' => $offerName,
                'offer_id' => $offerId,
                'hold_time' => $offerHoldTime,
                'instant_credit' => 0,    // 0 no, 1 yes,
                'ip_address' => $ipAddress,
                'status' => $finalStatus,
            ]);

            /*===================================reward or deduct user and upline==========================================================*/
            //if finalstatus = 0(completed), reward, and if 2(reversed), deduct the worker and upline and corresponding log
            if ( $finalStatus == 0 ) 
            {
                $user->addWorkerBalance($finalReward);
                $user->increment('diamond_level_balance', $addToExpertLevel);
                $user->increment('total_earned', $finalReward);
                $user->increment('earned_from_offers', $finalReward);
                $user->increment('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' => 'reward ' . $finalReward . ' added from notik',
                ]);
                if($uplineId != 0)
                {
                    $uplineToReward = User::find($uplineId);
                    $uplineToReward->addWorkerBalance( $uplineCommision );
                    $uplineToReward->increment('earned_from_referrals', $uplineCommision);
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'received referral Commission ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }
            elseif ( $finalStatus == 2 ) 
            {
                $user->deductWorkerBalance(abs($finalReward));
                $user->decrement('diamond_level_balance', abs($addToExpertLevel));
                $user->decrement('total_earned', abs($finalReward));
                $user->decrement('earned_from_offers', abs($finalReward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' =>  $finalReward . ' reversed by notik',
                ]);
                if($uplineId != 0)
                {
                    $uplineToDeduct = User::find($uplineId);
                    $uplineToDeduct->deductWorkerBalance( abs($uplineCommision) );
                    $uplineToDeduct->decrement('earned_from_referrals', abs($uplineCommision));
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'deducted chargedback ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }

         } elseif ( $transactionIdExists && $finalStatus == 2 )
         {
            $offerIdToReverse = OffersAndSurveysLog::where('transaction_id', $transactionId)->value('id');
            $offer = OffersAndSurveysLog::find($offerIdToReverse);
            $userIdToReverse = $offer->user_id;
            $user = User::find($userIdToReverse);
            if( $offer->status == 0 )
            {
                $user->deductWorkerBalance(abs($offer->reward));
                $user->decrement('diamond_level_balance', abs($offer->reward));
                $user->decrement('total_earned', abs($offer->reward));
                $user->decrement('earned_from_offers', abs($offer->reward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $user->id,
                    'description' =>  $offer->reward . ' reversed by notik',
                ]);
            }

            if($uplineId != 0)
            {
                $uplineToDeduct = User::find($uplineId);
                $uplineToDeduct->deductWorkerBalance( abs($offer->upline_commision) );
                $uplineToDeduct->decrement('earned_from_referrals', abs($offer->upline_commision));
                Log::create([
                    'user_id' => $uplineId, 
                    'description' => 'deducted chargedback ' . $offer->upline_commision . ' from user '. $user->username,
                ]);
            }
            $offer->update([ 'status' => 2 ]);
        }
         else
         {
            if( $request->has('rewarded_txn_id') )
            {
                $offerIdToReverse = OffersAndSurveysLog::where('transaction_id', $request->input('rewarded_txn_id'))->value('id');
                $offer = OffersAndSurveysLog::find($offerIdToReverse);
                $offer->update(['status' =>2]);
            }
            
            $user->deductWorkerBalance(abs($finalReward));
            $user->decrement('diamond_level_balance', abs($addToExpertLevel));
            $user->decrement('total_earned', abs($finalReward));
            $user->decrement('earned_from_offers', abs($finalReward));
            $user->decrement('total_offers_completed');
         }
         echo 1;
    }



    public function revlum(Request $request)
    {
        $offerwall = Offerwall::where('name', 'Revlum')->first();
        /*===================================Ip Whitelist and Check Offerwall Enabled or not==================================*/
        $ipAddress = $request->ip();
        // Get the whitelisted IPs from the database
        $whitelistedIps = json_decode($offerwall->whitelisted_ips, true);

        // Check if $ipAddress is in the whitelisted IPs
        if (in_array($ipAddress, $whitelistedIps)) {
            
        } else {
            // IP address is not whitelisted, take appropriate action
            return "Access Denied! IP address $ipAddress is not whitelisted!";
        }

        if ($offerwall->status != 1 ) {
            die();
            echo "offerwall is not enabled ";
        }
        /*===================================Get All common data from the postback==========================================================*/
        $uniqueUserId = $request->input();
        $payout = $request->input();
        $currencyAmount = $request->input();
        $transactionId = $request->input('ip_address');
        $ipAddress = $request->has('ip') ? $request->input('ip') : null;
        $offerName = $request->has('offer_name') ? $request->input('offer_name') : null;
        $offerId = $request->has('offer_id') ? $request->input('offer_id') : null;
        $hash = $request->input('hash');
        
     /*===================================check the hash security==========================================================*/
        $secret = $offerwall->secret_key;
        if(md5($uniqueUserId.$transactionId.$currencyAmount.$secret) != $hash)
        {
            echo "ERROR: Signature doesn't match";
           return;
        }

     /*===================================Do necessary Calculations==========================================================*/


        $userId = User::where('unique_user_id', $uniqueUserId)->value('id');
        $user = User::find($userId);
        $refCommissionPercentage = $offerwall->ref_commission;
        $offerHold = $offerwall->hold;

        $userLevel = $user->level;
        $expertCp = $offerwall->expert_cp;
        if ( $userLevel == 0 ) 
        {
            $finalReward = ( $payout / 100 ) * $offerwall->starter_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 1 )
        {
            $finalReward = ( $payout / 100 ) * $offerwall->advance_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 2 ) {
            $finalReward = ( $payout / 100 ) * $offerwall->expert_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }else
        {
            Log::create([
                'user_id' => $userId, 
                'description' => 'user level is not specified',
            ]);
        }

        // if the transactionId starts with r- , it is a reverse transaction
        if (preg_match('/^r-(.*)/', $transactionId, $matches)) {
            // transactionId starts with "r-", $matches[1] contains the rest of the string
            $transactionId = $matches[1];
            $finalStatus = 0; //0=reversed
        }


        if( $request->has('status') ) 
        {
            if ($request->input('status') == 1 && $offerHold == 0 ) 
            {
                $finalStatus = 0; //0, completed, 1 on hold / pending, 2 reversed.
            }
            elseif ($request->input('status') == 1 && $finalReward <= $offerwall->tier1_hold_amount )
            {
                $finalStatus = 0;
            }
            elseif ( $request->input('status') == 1 && $offerHold == 1 && $offerwall->tier1_hold_amount < $finalReward && $currencyAmount > 0 && $payout > 0 )  //the complete status value of the provider offerwall
            {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 2;
            }
        }
        elseif ( $currencyAmount < 0 || $payout < 0)
        {
            $finalStatus = 2;
        }
        else
        {
            if ( $offerHold == 1 && $finalReward > $offerwall->tier1_hold_amount) {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 0;
            }
            
        }

        //check if user has any upline
        $uplineId = User::where('id', $userId)->value('upline');
        if( $uplineId != 0 )
        {
            $upline = User::find($uplineId);
            $uplineCommision = abs($finalReward) / 100 * $refCommissionPercentage;
        } else
        {
            $uplineCommision = 0;
        }
        

        //if hold is enabled, calculate the hold time based on the reward
        if ( $offerwall->hold == 1 ) {
            if (  $finalReward >= $offerwall->tier1_hold_amount && $finalReward < $offerwall->tier2_hold_amount ) {
                $offerHoldTime = $offerwall->tier1_hold_time;
            }elseif ( $finalReward >= $offerwall->tier2_hold_amount && $finalReward < $offerwall->tier3_hold_amount ) {
                $offerHoldTime = $offerwall->tier2_hold_time;
            }
            else{
                $offerHoldTime = $offerwall->tier3_hold_time;
            }
        }else{ $offerHoldTime =0; }
        //check if transaction found in the database
        $transactionIdExists = OffersAndSurveysLog::where('transaction_id', $transactionId)->where('provider_name', $offerwall->name)->exists();

     /*===================================Create Log if complete, update if trx found and reversed ==========================================================*/
        //if transaction does not exist, create log
         if (!$transactionIdExists) 
         {
            OffersAndSurveysLog::create([
                'user_id' => $userId,
                'provider_name' => $offerwall->name,
                'payout' => $payout,
                'reward' => $finalReward,
                'added_expert_level' => $addToExpertLevel,
                'upline_commision' => $uplineCommision,
                'transaction_id' => $transactionId,
                'offer_name' => $offerName,
                'offer_id' => $offerId,
                'hold_time' => $offerHoldTime,
                'instant_credit' => 0,    // 0 no, 1 yes,
                'ip_address' => $ipAddress,
                'status' => $finalStatus,
            ]);

            /*===================================reward or deduct user and upline==========================================================*/
            //if finalstatus = 0(completed), reward, and if 2(reversed), deduct the worker and upline and corresponding log
            if ( $finalStatus == 0 ) 
            {
                $user->addWorkerBalance($finalReward);
                $user->increment('diamond_level_balance', $addToExpertLevel);
                $user->increment('total_earned', $finalReward);
                $user->increment('earned_from_offers', $finalReward);
                $user->increment('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' => 'reward ' . $finalReward . ' added from revlum',
                ]);
                if($uplineId != 0)
                {
                    $uplineToReward = User::find($uplineId);
                    $uplineToReward->addWorkerBalance( $uplineCommision );
                    $uplineToReward->increment('earned_from_referrals', $uplineCommision);
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'received referral Commission ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }
            elseif ( $finalStatus == 2 ) 
            {
                $user->deductWorkerBalance(abs($finalReward));
                $user->decrement('diamond_level_balance', abs($addToExpertLevel));
                $user->decrement('total_earned', abs($finalReward));
                $user->decrement('earned_from_offers', abs($finalReward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' =>  $finalReward . ' reversed by revlum',
                ]);
                if($uplineId != 0)
                {
                    $uplineToDeduct = User::find($uplineId);
                    $uplineToDeduct->deductWorkerBalance( abs($uplineCommision) );
                    $uplineToDeduct->decrement('earned_from_referrals', abs($uplineCommision));
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'deducted chargedback ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }

         } elseif ( $transactionIdExists && $finalStatus == 2 )
         {
            $offerIdToReverse = OffersAndSurveysLog::where('transaction_id', $transactionId)->value('id');
            $offer = OffersAndSurveysLog::find($offerIdToReverse);
            $userIdToReverse = $offer->user_id;
            $user = User::find($userIdToReverse);
            if( $offer->status == 0 )
            {
                $user->deductWorkerBalance(abs($offer->reward));
                $user->decrement('diamond_level_balance', abs($offer->reward));
                $user->decrement('total_earned', abs($offer->reward));
                $user->decrement('earned_from_offers', abs($offer->reward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $user->id,
                    'description' =>  $offer->reward . ' reversed by revlum',
                ]);
            }

            if($uplineId != 0)
            {
                $uplineToDeduct = User::find($uplineId);
                $uplineToDeduct->deductWorkerBalance( abs($offer->upline_commision) );
                $uplineToDeduct->decrement('earned_from_referrals', abs($offer->upline_commision));
                Log::create([
                    'user_id' => $uplineId, 
                    'description' => 'deducted chargedback ' . $offer->upline_commision . ' from user '. $user->username,
                ]);
            }
            $offer->update([ 'status' => 2 ]);
        }
         else
         {
            $user->deductWorkerBalance(abs($finalReward));
            $user->decrement('diamond_level_balance', abs($addToExpertLevel));
            $user->decrement('total_earned', abs($finalReward));
            $user->decrement('earned_from_offers', abs($finalReward));
            $user->decrement('total_offers_completed');
         }
         echo 1;
         die();
    }



    public function timewall(Request $request)
    {
        $offerwall = Offerwall::where('name', 'Timewall')->first();
        /*===================================Ip Whitelist and Check Offerwall Enabled or not==================================*/
        $ipAddress = $request->ip();
        // Get the whitelisted IPs from the database
        $whitelistedIps = json_decode($offerwall->whitelisted_ips, true);

        // Check if $ipAddress is in the whitelisted IPs
        if (in_array($ipAddress, $whitelistedIps)) {
            
        } else {
            // IP address is not whitelisted, take appropriate action
            return "Access Denied! IP address $ipAddress is not whitelisted!";
        }

        if ($offerwall->status != 1 ) {
            die();
            echo "offerwall is not enabled ";
        }
        /*===================================Get All common data from the postback==========================================================*/
        $uniqueUserId = $request->input('user_id');
        $payout = $request->input('payout');
        $currencyAmount = $request->input('reward');
        $transactionId = $request->input('transaction_id');
        $ipAddress = $request->has('ip') ? $request->input('ip') : null;
        $offerName = $request->has('offer_name') ? $request->input('offer_name') : null;
        $offerId = $request->has('offer_id') ? $request->input('offer_id') : null;
        $hash = $request->input('hash');
        
     /*===================================check the hash security==========================================================*/
        $security_key = hash("sha256", $uniqueUserId . $payout . $offerwall->secret_key);
        if ($security_key !== $hash) {
            exit('fail');
        }

     /*===================================Do necessary Calculations==========================================================*/


        $userId = User::where('unique_user_id', $uniqueUserId)->value('id');
        $user = User::find($userId);
        $refCommissionPercentage = $offerwall->ref_commission;
        $offerHold = $offerwall->hold;

        $userLevel = $user->level;
        $expertCp = $offerwall->expert_cp;
        if ( $userLevel == 0 ) 
        {
            $finalReward = ( $payout / 100 ) * $offerwall->starter_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 1 )
        {
            $finalReward = ( $payout / 100 ) * $offerwall->advance_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 2 ) {
            $finalReward = ( $payout / 100 ) * $offerwall->expert_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }else
        {
            Log::create([
                'user_id' => $userId, 
                'description' => 'user level is not specified',
            ]);
        }

        // if the transactionId starts with r- , it is a reverse transaction
        if (preg_match('/^r-(.*)/', $transactionId, $matches)) {
            // transactionId starts with "r-", $matches[1] contains the rest of the string
            $transactionId = $matches[1];
            $finalStatus = 0; //0=reversed
        }


        if( $request->has('status') ) 
        {
            if ($request->input('status') == 'credit' && $offerHold == 0 ) 
            {
                $finalStatus = 0; //0, completed, 1 on hold / pending, 2 reversed.
            }
            elseif ($request->input('status') == 'credit' && $finalReward <= $offerwall->tier1_hold_amount )
            {
                $finalStatus = 0;
            }
            elseif ( $request->input('status') == 'credit' && $offerHold == 1 && $offerwall->tier1_hold_amount < $finalReward && $currencyAmount > 0 && $payout > 0 )  //the complete status value of the provider offerwall
            {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 2;
            }
        }
        elseif ( $currencyAmount < 0 || $payout < 0)
        {
            $finalStatus = 2;
        }
        else
        {
            if ( $offerHold == 1 && $finalReward > $offerwall->tier1_hold_amount) {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 0;
            }
            
        }

        //check if user has any upline
        $uplineId = User::where('id', $userId)->value('upline');
        if( $uplineId != 0 )
        {
            $upline = User::find($uplineId);
            $uplineCommision = abs($finalReward) / 100 * $refCommissionPercentage;
        } else
        {
            $uplineCommision = 0;
        }
        

        //if hold is enabled, calculate the hold time based on the reward
        if ( $offerwall->hold == 1 ) {
            if (  $finalReward >= $offerwall->tier1_hold_amount && $finalReward < $offerwall->tier2_hold_amount ) {
                $offerHoldTime = $offerwall->tier1_hold_time;
            }elseif ( $finalReward >= $offerwall->tier2_hold_amount && $finalReward < $offerwall->tier3_hold_amount ) {
                $offerHoldTime = $offerwall->tier2_hold_time;
            }
            else{
                $offerHoldTime = $offerwall->tier3_hold_time;
            }
        }else{ $offerHoldTime =0; }
        //check if transaction found in the database
        $transactionIdExists = OffersAndSurveysLog::where('transaction_id', $transactionId)->where('provider_name', $offerwall->name)->exists();

     /*===================================Create Log if complete, update if trx found and reversed ==========================================================*/
        //if transaction does not exist, create log
         if (!$transactionIdExists) 
         {
            OffersAndSurveysLog::create([
                'user_id' => $userId,
                'provider_name' => $offerwall->name,
                'payout' => $payout,
                'reward' => $finalReward,
                'added_expert_level' => $addToExpertLevel,
                'upline_commision' => $uplineCommision,
                'transaction_id' => $transactionId,
                'offer_name' => $offerName,
                'offer_id' => $offerId,
                'hold_time' => $offerHoldTime,
                'instant_credit' => 0,    // 0 no, 1 yes,
                'ip_address' => $ipAddress,
                'status' => $finalStatus,
            ]);

            /*===================================reward or deduct user and upline==========================================================*/
            //if finalstatus = 0(completed), reward, and if 2(reversed), deduct the worker and upline and corresponding log
            if ( $finalStatus == 0 ) 
            {
                $user->addWorkerBalance($finalReward);
                $user->increment('diamond_level_balance', $addToExpertLevel);
                $user->increment('total_earned', $finalReward);
                $user->increment('earned_from_offers', $finalReward);
                $user->increment('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' => 'reward ' . $finalReward . ' added from timewall',
                ]);
                if($uplineId != 0)
                {
                    $uplineToReward = User::find($uplineId);
                    $uplineToReward->addWorkerBalance( $uplineCommision );
                    $uplineToReward->increment('earned_from_referrals', $uplineCommision);
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'received referral Commission ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }
            elseif ( $finalStatus == 2 ) 
            {
                $user->deductWorkerBalance(abs($finalReward));
                $user->decrement('diamond_level_balance', abs($addToExpertLevel));
                $user->decrement('total_earned', abs($finalReward));
                $user->decrement('earned_from_offers', abs($finalReward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' =>  $finalReward . ' reversed by timewall',
                ]);
                if($uplineId != 0)
                {
                    $uplineToDeduct = User::find($uplineId);
                    $uplineToDeduct->deductWorkerBalance( abs($uplineCommision) );
                    $uplineToDeduct->decrement('earned_from_referrals', abs($uplineCommision));
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'deducted chargedback ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }

         } elseif ( $transactionIdExists && $finalStatus == 2 )
         {
            $offerIdToReverse = OffersAndSurveysLog::where('transaction_id', $transactionId)->value('id');
            $offer = OffersAndSurveysLog::find($offerIdToReverse);
            $userIdToReverse = $offer->user_id;
            $user = User::find($userIdToReverse);
            if( $offer->status == 0 )
            {
                $user->deductWorkerBalance(abs($offer->reward));
                $user->decrement('diamond_level_balance', abs($offer->reward));
                $user->decrement('total_earned', abs($offer->reward));
                $user->decrement('earned_from_offers', abs($offer->reward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $user->id,
                    'description' =>  $offer->reward . ' reversed by timewall',
                ]);
            }

            if($uplineId != 0)
            {
                $uplineToDeduct = User::find($uplineId);
                $uplineToDeduct->deductWorkerBalance( abs($offer->upline_commision) );
                $uplineToDeduct->decrement('earned_from_referrals', abs($offer->upline_commision));
                Log::create([
                    'user_id' => $uplineId, 
                    'description' => 'deducted chargedback ' . $offer->upline_commision . ' from user '. $user->username,
                ]);
            }
            $offer->update([ 'status' => 2 ]);
        }
         else
         {
            $user->deductWorkerBalance(abs($finalReward));
            $user->decrement('diamond_level_balance', abs($addToExpertLevel));
            $user->decrement('total_earned', abs($finalReward));
            $user->decrement('earned_from_offers', abs($finalReward));
            $user->decrement('total_offers_completed');
         }
        echo 1;
        die();
    }


    public function wannads(Request $request)
    {
        $offerwall = Offerwall::where('name', 'Wannads')->first();
        /*===================================Ip Whitelist and Check Offerwall Enabled or not==================================*/ 
        $ipAddress = $request->ip();
        // Get the whitelisted IPs from the database
        $whitelistedIps = json_decode($offerwall->whitelisted_ips, true);

        // Check if $ipAddress is in the whitelisted IPs
        if (in_array($ipAddress, $whitelistedIps)) {
            
        } else {
            // IP address is not whitelisted, take appropriate action
            return "Access Denied! IP address $ipAddress is not whitelisted!";
        }

        if ($offerwall->status != 1 ) {
            die();
            echo "offerwall is not enabled ";
        }
        /*===================================Get All common data from the postback==========================================================*/
        $uniqueUserId = $request->input('subId');
        $payout = $request->input('payout');
        $currencyAmount = $request->input('reward');
        $transactionId = $request->input('transId');
        $ipAddress = $request->has('userIp') ? $request->input('userIp') : null;
        $offerName = $request->has('campaign_name') ? $request->input('campaign_name') : null;
        $offerId = $request->has('campaign_id') ? $request->input('campaign_id') : null;
        $hash = $request->input('signature');
        
     /*===================================check the hash security==========================================================*/
        if(md5($uniqueUserId.$transactionId.$currencyAmount.$offerwall->secret_key) != $hash)
        {
            echo "ERROR: Signature doesn't match";
            return;
        }

     /*===================================Do necessary Calculations==========================================================*/


        $userId = User::where('unique_user_id', $uniqueUserId)->value('id');
        $user = User::find($userId);
        $refCommissionPercentage = $offerwall->ref_commission;
        $offerHold = $offerwall->hold;

        $userLevel = $user->level;
        $expertCp = $offerwall->expert_cp;
        if ( $userLevel == 0 ) 
        {
            $finalReward = ( $payout / 100 ) * $offerwall->starter_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 1 )
        {
            $finalReward = ( $payout / 100 ) * $offerwall->advance_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }elseif ( $userLevel == 2 ) {
            $finalReward = ( $payout / 100 ) * $offerwall->expert_cp;
            $addToExpertLevel = ( ( $expertCp / 100 ) * $payout ) - $finalReward;
        }else
        {
            Log::create([
                'user_id' => $userId, 
                'description' => 'user level is not specified',
            ]);
        }

        // if the transactionId starts with r- , it is a reverse transaction
        if (preg_match('/^r-(.*)/', $transactionId, $matches)) {
            // transactionId starts with "r-", $matches[1] contains the rest of the string
            $transactionId = $matches[1];
            $finalStatus = 0; //0=reversed
        }


        if( $request->has('status') ) 
        {
            if ($request->input('status') == 1 && $offerHold == 0 ) 
            {
                $finalStatus = 0; //0, completed, 1 on hold / pending, 2 reversed.
            }
            elseif ($request->input('status') == 1 && $finalReward <= $offerwall->tier1_hold_amount )
            {
                $finalStatus = 0;
            }
            elseif ( $request->input('status') == 1 && $offerHold == 1 && $offerwall->tier1_hold_amount < $finalReward && $currencyAmount > 0 && $payout > 0 )  //the complete status value of the provider offerwall
            {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 2;
            }
        }
        elseif ( $currencyAmount < 0 || $payout < 0)
        {
            $finalStatus = 2;
        }
        else
        {
            if ( $offerHold == 1 && $finalReward > $offerwall->tier1_hold_amount) {
                $finalStatus = 1;
            }
            else
            {
                $finalStatus = 0;
            }
            
        }

        //check if user has any upline
        $uplineId = User::where('id', $userId)->value('upline');
        if( $uplineId != 0 )
        {
            $upline = User::find($uplineId);
            $uplineCommision = abs($finalReward) / 100 * $refCommissionPercentage;
        } else
        {
            $uplineCommision = 0;
        }
        

        //if hold is enabled, calculate the hold time based on the reward
        if ( $offerwall->hold == 1 ) {
            if (  $finalReward >= $offerwall->tier1_hold_amount && $finalReward < $offerwall->tier2_hold_amount ) {
                $offerHoldTime = $offerwall->tier1_hold_time;
            }elseif ( $finalReward >= $offerwall->tier2_hold_amount && $finalReward < $offerwall->tier3_hold_amount ) {
                $offerHoldTime = $offerwall->tier2_hold_time;
            }
            else{
                $offerHoldTime = $offerwall->tier3_hold_time;
            }
        }else{ $offerHoldTime =0; }
        //check if transaction found in the database
        $transactionIdExists = OffersAndSurveysLog::where('transaction_id', $transactionId)->where('provider_name', $offerwall->name)->exists();

     /*===================================Create Log if complete, update if trx found and reversed ==========================================================*/
        //if transaction does not exist, create log
         if (!$transactionIdExists) 
         {
            OffersAndSurveysLog::create([
                'user_id' => $userId,
                'provider_name' => $offerwall->name,
                'payout' => $payout,
                'reward' => $finalReward,
                'added_expert_level' => $addToExpertLevel,
                'upline_commision' => $uplineCommision,
                'transaction_id' => $transactionId,
                'offer_name' => $offerName,
                'offer_id' => $offerId,
                'hold_time' => $offerHoldTime,
                'instant_credit' => 0,    // 0 no, 1 yes,
                'ip_address' => $ipAddress,
                'status' => $finalStatus,
            ]);

            /*===================================reward or deduct user and upline==========================================================*/
            //if finalstatus = 0(completed), reward, and if 2(reversed), deduct the worker and upline and corresponding log
            if ( $finalStatus == 0 ) 
            {
                $user->addWorkerBalance($finalReward);
                $user->increment('diamond_level_balance', $addToExpertLevel);
                $user->increment('total_earned', $finalReward);
                $user->increment('earned_from_offers', $finalReward);
                $user->increment('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' => 'reward ' . $finalReward . ' added from wannads',
                ]);
                if($uplineId != 0)
                {
                    $uplineToReward = User::find($uplineId);
                    $uplineToReward->addWorkerBalance( $uplineCommision );
                    $uplineToReward->increment('earned_from_referrals', $uplineCommision);
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'received referral Commission ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }
            elseif ( $finalStatus == 2 ) 
            {
                $user->deductWorkerBalance(abs($finalReward));
                $user->decrement('diamond_level_balance', abs($addToExpertLevel));
                $user->decrement('total_earned', abs($finalReward));
                $user->decrement('earned_from_offers', abs($finalReward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $userId,
                    'description' =>  $finalReward . ' reversed by wannads',
                ]);
                if($uplineId != 0)
                {
                    $uplineToDeduct = User::find($uplineId);
                    $uplineToDeduct->deductWorkerBalance( abs($uplineCommision) );
                    $uplineToDeduct->decrement('earned_from_referrals', abs($uplineCommision));
                    Log::create([
                        'user_id' => $uplineId, 
                        'description' => 'deducted chargedback ' . $uplineCommision . ' from user '. $user->username,
                    ]);
                }
            }

         } elseif ( $transactionIdExists && $finalStatus == 2 )
         {
            $offerIdToReverse = OffersAndSurveysLog::where('transaction_id', $transactionId)->value('id');
            $offer = OffersAndSurveysLog::find($offerIdToReverse);
            $userIdToReverse = $offer->user_id;
            $user = User::find($userIdToReverse);
            if( $offer->status == 0 )
            {
                $user->deductWorkerBalance(abs($offer->reward));
                $user->decrement('diamond_level_balance', abs($offer->reward));
                $user->decrement('total_earned', abs($offer->reward));
                $user->decrement('earned_from_offers', abs($offer->reward));
                $user->decrement('total_offers_completed');
                Log::create([
                    'user_id' => $user->id,
                    'description' =>  $offer->reward . ' reversed by wannads',
                ]);
            }

            if($uplineId != 0)
            {
                $uplineToDeduct = User::find($uplineId);
                $uplineToDeduct->deductWorkerBalance( abs($offer->upline_commision) );
                $uplineToDeduct->decrement('earned_from_referrals', abs($offer->upline_commision));
                Log::create([
                    'user_id' => $uplineId, 
                    'description' => 'deducted chargedback ' . $offer->upline_commision . ' from user '. $user->username,
                ]);
            }
            $offer->update([ 'status' => 2 ]);
        }
         else
         {
            $user->deductWorkerBalance(abs($finalReward));
            $user->decrement('diamond_level_balance', abs($addToExpertLevel));
            $user->decrement('total_earned', abs($finalReward));
            $user->decrement('earned_from_offers', abs($finalReward));
            $user->decrement('total_offers_completed');
         }
        echo "OK";
        die();
    }




}
