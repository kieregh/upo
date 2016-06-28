<?php
/*
  PHP realisation of Paypal recurring payment profile API
  Autor: Serge Autushka S.Autushka@gmail.com (2012)

  "PayPal" is trademark of PayPal
 Legal Agreements for PayPal Services
 https://cms.paypal.com/us/cgi-bin/marketingweb?cmd=_render-content&content_ID=ua/Legal_Hub_full&locale.x=en_US

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

 */
class PaypalRecurringPaymentProfile
{
  private $_api_username;
  private $_api_password;
  private $_api_signature;
  private $_api_endpoint;
  private $_api_version;

  const autobill_NoAutoBill       = 'NoAutoBill';
  const autobill_AddToNextBilling = 'AddToNextBilling';
  const period_Day                = 'Day';
  const period_Week               = 'Week';
  const period_SemiMonth          = 'SemiMonth';
  const period_Month              = 'Month';
  const period_Year               = 'Year';
  const ContinueOnFailure         = 'ContinueOnFailure ';
  const CancelOnFailure           = 'CancelOnFailure';
  const cc_Visa                   = 'Visa';
  const cc_MasterCard             = 'MasterCard';
  const cc_Discover               = 'Discover';
  const cc_Amex                   = 'Amex';
  const cc_Maestro                = 'Maestro';
  const payer_verified            = 'verified';
  const payer_unverified          = 'unverified';
  const item_type_Digital         = 'Digital';
  const item_type_Physical        = 'Physical';
  const action_Cancel             = 'Cancel';
  const action_Suspend            = 'Suspend';
  const action_Reactivate         = 'Reactivate';
  const env_type_life             = 'life';
  const env_type_sandbox          = 'sandbox';

  public function __construct($api_username, $api_pasword, $api_signature, $api_version = '75.0', $env_type = 'sandbox')
  {

    $this->_api_username = $api_username;
    $this->_api_password = $api_pasword;
    $this->_api_signature = $api_signature;
    $this->_api_version = $api_version;
    if ($env_type === self::env_type_sandbox)
      $this->_api_endpoint = "https://api-3t.sandbox.paypal.com/nvp";
    elseif ($env_type === self::env_type_life)
      $this->_api_endpoint = "https://api-3t.paypal.com/nvp";
  }

  private function paypalHttpPostRequest($method, $nvp_params)
  {

// curl parameters initialization.
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $this->_api_endpoint);
    curl_setopt($curl, CURLOPT_VERBOSE, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);

    $nvp_request = "METHOD=" . $method . "&VERSION=" . $this->_api_version . "&PWD=" . $this->_api_password . "&USER=" . $this->_api_username . "&SIGNATURE=" . $this->_api_signature . $nvp_params;
    curl_setopt($curl, CURLOPT_POSTFIELDS, $nvp_request);

    $response = curl_exec($curl);

    if (!$response) {
      return false;
    }

//Prepare response.
    $response_attributs        = explode("&", $response);
    $response_parsed_attributs = array();

    foreach ($response_attributs as $cur_atr) {
      $tmp_atr = explode("=", $cur_atr);
      if (count($tmp_atr) == 2) {
        $response_parsed_attributs[urldecode($tmp_atr[0])] = urldecode($tmp_atr[1]);
      }
    }
    if (!isset($response_parsed_attributs['ACK'])) {
      return false;
    }
    return $response_parsed_attributs;
  }

  //API FUNCTION
  public function createProfile(array $data)
  {

    $_errors = array();
    $nvp_params = array();

//Recurring Payments Profile Details Fields

    if (array_key_exists('SUBSCRIBERNAME', $data))
      $nvp_params['SUBSCRIBERNAME'] = urlencode($data['SUBSCRIBERNAME']);
    if (array_key_exists('PROFILESTARTDATE', $data)) {
      $nvp_params['PROFILESTARTDATE'] = urlencode($data['PROFILESTARTDATE']);
    }
    else {
      $_errors[]                      = 'PROFILESTARTDATE is required parameter';
    }
    if (array_key_exists('PROFILEREFERENCE', $data))
      $nvp_params['PROFILEREFERENCE'] = urlencode($data['PROFILEREFERENCE']);

//Schedule Details Fields

    if (array_key_exists('DESC', $data))
      $nvp_params['DESC']              = urlencode($data['DESC']);
    else
      $_errors[]                       = 'DESC is required parameter';
    if (array_key_exists('MAXFAILEDPAYMENTS', $data) && (int) $data['MAXFAILEDPAYMENTS'] > 0)
      $nvp_params['MAXFAILEDPAYMENTS'] = (int) $data['PROFILEREFERENCE'];
    if (array_key_exists('AUTOBILLOUTAMT', $data))
      $nvp_params['AUTOBILLOUTAMT']    = $data['AUTOBILLOUTAMT'];

//Billing Period Details Fields

    if (array_key_exists('BILLINGPERIOD', $data))
      $nvp_params['BILLINGPERIOD'] = urlencode($data['BILLINGPERIOD']);
    else
      $_errors[]                   = 'BILLINGPERIOD is required parameter';

    if (array_key_exists('BILLINGFREQUENCY', $data))
      $nvp_params['BILLINGFREQUENCY']        = (int) $data['BILLINGFREQUENCY'];
    else
      $_errors[]                             = 'BILLINGFREQUENCY is required parameter';
    if (array_key_exists('TOTALBILLINGCYCLES', $data))
      $nvp_params['TOTALBILLINGCYCLES']      = (int) $data['TOTALBILLINGCYCLES'];
    if (array_key_exists('AMT', $data))
      $nvp_params['AMT']                     = (float) $data['AMT'];
    else
      $_errors[]                             = 'AMT is required parameter';
    if (array_key_exists('TRIALBILLINGPERIOD', $data))
      $nvp_params['TRIALBILLINGPERIOD']      = urlencode($data['TRIALBILLINGPERIOD']);
    if (array_key_exists('TRIALBILLINGFREQUENCY', $data))
      $nvp_params['TRIALBILLINGFREQUENCY']   = (int) $data['TRIALBILLINGFREQUENCY'];
    if (array_key_exists('TRIALTOTALBILLINGCYCLES', $data))
      $nvp_params['TRIALTOTALBILLINGCYCLES'] = (int) $data['TRIALTOTALBILLINGCYCLES'];
    if (array_key_exists('TRIALAMT', $data))
      $nvp_params['TRIALAMT']                = (float) $data['TRIALAMT'];
    if (array_key_exists('CURRENCYCODE', $data))
      $nvp_params['CURRENCYCODE']            = urlencode($data['CURRENCYCODE']);
    else
      $_errors[]                             = 'CURRENCYCODE is required parameter';
    if (array_key_exists('SHIPPINGAMT', $data))
      $nvp_params['SHIPPINGAMT']             = (float) $data['SHIPPINGAMT'];
    if (array_key_exists('TAXAMT', $data))
      $nvp_params['TAXAMT']                  = (float) $data['TAXAMT'];

    //Actvation Details Fields
    if (array_key_exists('INITAMT', $data))
      $nvp_params['INITAMT']             = (float) $data['INITAMT'];
    if (array_key_exists('FAILEDINITAMTACTION', $data))
      $nvp_params['FAILEDINITAMTACTION'] = urlencode($data['FAILEDINITAMTACTION']);

    //Ship To Address Fields
    if (array_key_exists('SHIPTONAME', $data))
      $nvp_params['SHIPTONAME']     = urlencode($data['SHIPTONAME']);
    if (array_key_exists('SHIPTOSTREET', $data))
      $nvp_params['SHIPTOSTREET']   = urlencode($data['SHIPTOSTREET']);
    if (array_key_exists('SHIPTOSTREET2', $data))
      $nvp_params['SHIPTOSTREET2']  = urlencode($data['SHIPTOSTREET2']);
    if (array_key_exists('SHIPTOCITY', $data))
      $nvp_params['SHIPTOCITY']     = urlencode($data['SHIPTOCITY']);
    if (array_key_exists('SHIPTOSTATE', $data))
      $nvp_params['SHIPTOSTATE']    = urlencode($data['SHIPTOSTATE']);
    if (array_key_exists('SHIPTOZIP', $data))
      $nvp_params['SHIPTOZIP']      = urlencode($data['SHIPTOZIP']);
    if (array_key_exists('SHIPTOCOUNTRY', $data))
      $nvp_params['SHIPTOCOUNTRY']  = urlencode($data['SHIPTOCOUNTRY']);
    if (array_key_exists('SHIPTOPHONENUM', $data))
      $nvp_params['SHIPTOPHONENUM'] = urlencode($data['SHIPTOPHONENUM']);

    //Credit Card Details Fields
    if (array_key_exists('CREDITCARDTYPE', $data))
      $nvp_params['CREDITCARDTYPE'] = urlencode($data['CREDITCARDTYPE']);
    if (array_key_exists('ACCT', $data))
      $nvp_params['ACCT']           = urlencode($data['ACCT']);
    else
      $_errors[]                    = 'ACCT is required parameter';
    if (array_key_exists('EXPDATE', $data))
      $nvp_params['EXPDATE']        = urlencode($data['EXPDATE']);
    if (array_key_exists('CVV2', $data))
      $nvp_params['CVV2']           = urlencode($data['CVV2']);
    if (array_key_exists('STARTDATE', $data))
      $nvp_params['STARTDATE']      = urlencode($data['STARTDATE']);
    if (array_key_exists('ISSUENUMBER', $data))
      $nvp_params['ISSUENUMBER']    = urlencode($data['ISSUENUMBER']);

    //Payer Information Fields

    if (array_key_exists('EMAIL', $data))
      $nvp_params['EMAIL']       = urlencode($data['EMAIL']);
    if (array_key_exists('PAYERID', $data))
      $nvp_params['PAYERID']     = urlencode($data['PAYERID']);
    if (array_key_exists('PAYERSTATUS', $data))
      $nvp_params['PAYERSTATUS'] = urlencode($data['PAYERSTATUS']);
    if (array_key_exists('COUNTRYCODE', $data))
      $nvp_params['COUNTRYCODE'] = urlencode($data['COUNTRYCODE']);
    if (array_key_exists('BUSINESS', $data))
      $nvp_params['BUSINESS']    = urlencode($data['BUSINESS']);

    //Payer Name Fields
    if (array_key_exists('SALUTATION', $data))
      $nvp_params['SALUTATION'] = urlencode($data['SALUTATION']);
    if (array_key_exists('FIRSTNAME', $data))
      $nvp_params['FIRSTNAME']  = urlencode($data['FIRSTNAME']);
    if (array_key_exists('MIDDLENAME', $data))
      $nvp_params['MIDDLENAME'] = urlencode($data['MIDDLENAME']);
    if (array_key_exists('LASTNAME', $data))
      $nvp_params['LASTNAME']   = urlencode($data['LASTNAME']);
    if (array_key_exists('SUFFIX', $data))
      $nvp_params['SUFFIX']     = urlencode($data['SUFFIX']);

    //Address Fields

    if (array_key_exists('STREET', $data))
      $nvp_params['STREET']  = urlencode($data['STREET']);
    if (array_key_exists('STREET2', $data))
      $nvp_params['STREET2'] = urlencode($data['STREET2']);
    if (array_key_exists('CITY', $data))
      $nvp_params['CITY']    = urlencode($data['CITY']);
    if (array_key_exists('STATE', $data))
      $nvp_params['STATE']   = urlencode($data['STATE']);
    if (array_key_exists('ZIP', $data))
      $nvp_params['ZIP']     = urlencode($data['ZIP']);
    if (array_key_exists('COUNTRY', $data))
      $nvp_params['COUNTRY'] = urlencode($data['COUNTRY']);

    //Payment Details Item Fields

    if (isset($data['ITEMS']) && is_array($data['ITEMS']))
      for ($i = 0; $i < count($data['ITEMS']); $i++)
        if (is_array($data['ITEMS'][$i])) {
          if (array_key_exists('L_PAYMENTREQUEST_n_ITEMCATEGORY', $data['ITEMS'][$i]))
            $nvp_params['L_PAYMENTREQUEST_n_ITEMCATEGORY' . $i] = urlencode($data['ITEMS'][$i]['L_PAYMENTREQUEST_n_ITEMCATEGORY']);
          if (array_key_exists('L_PAYMENTREQUEST_n_NAME', $data['ITEMS'][$i]))
            $nvp_params['L_PAYMENTREQUEST_n_NAME' . $i]         = urlencode($data['ITEMS'][$i]['L_PAYMENTREQUEST_n_NAME']);
          if (array_key_exists('L_PAYMENTREQUEST_n_DESC', $data['ITEMS'][$i]))
            $nvp_params['L_PAYMENTREQUEST_n_DESC' . $i]         = urlencode($data['ITEMS'][$i]['L_PAYMENTREQUEST_n_DESC']);
          if (array_key_exists('L_PAYMENTREQUEST_n_AMT', $data['ITEMS'][$i]))
            $nvp_params['L_PAYMENTREQUEST_n_AMT' . $i]          = (float) $data['ITEMS'][$i]['L_PAYMENTREQUEST_n_AMT'];
          if (array_key_exists('L_PAYMENTREQUEST_n_NUMBER', $data['ITEMS'][$i]))
            $nvp_params['L_PAYMENTREQUEST_n_NUMBER' . $i]       = urlencode($data['ITEMS'][$i]['L_PAYMENTREQUEST_n_NUMBER']);
          if (array_key_exists('L_PAYMENTREQUEST_n_QTY', $data['ITEMS'][$i]))
            $nvp_params['L_PAYMENTREQUEST_n_QTY' . $i]          = (int) $data['ITEMS'][$i]['L_PAYMENTREQUEST_n_QTY'];
          if (array_key_exists('L_PAYMENTREQUEST_n_TAXAMT', $data['ITEMS'][$i]))
            $nvp_params['L_PAYMENTREQUEST_n_TAXAMT' . $i]       = (float) $data['ITEMS'][$i]['L_PAYMENTREQUEST_n_TAXAMT'];
        }

    if (count($_errors)) {
      $_errors['has_errors'] = true;
      return $_errors;
    }
    $request_param         = '';
    foreach ($nvp_params as $index => $value)
      $request_param.='&' . $index . '=' . $value;
    $result                = $this->paypalHttpPostRequest('CreateRecurringPaymentsProfile', $request_param);
    return $result;
  }

  public function getProfileDetails($profile_id)
  {
	
    $request_param = '&PROFILEID=' . urlencode($profile_id);
    $result        = $this->paypalHttpPostRequest('GetRecurringPaymentsProfileDetails', $request_param);
    return $result;
  }

  public function manageProfileStatus($profile_id, $action, $note = '')
  {
    $request_param = '&PROFILEID=' . urlencode($profile_id) . '&ACTION=' . $action;
    if ($note)
      $request_param.='&NOTE=' . urlencode($note);
    $result        = $this->paypalHttpPostRequest('ManageRecurringPaymentsProfileStatus', $request_param);
    return $result;
  }

  public function updateProfile($profile_id, $data)
  {
    $nvp_params = array();

//Recurring Payments Profile Details Fields

    $nvp_params['PROFILEID']               = urlencode($profile_id);
    if (array_key_exists('NOTE', $data))
      $nvp_params['NOTE']                    = urlencode($data['NOTE']);
    if (array_key_exists('SUBSCRIBERNAME', $data))
      $nvp_params['SUBSCRIBERNAME']          = urlencode($data['SUBSCRIBERNAME']);
    if (array_key_exists('PROFILESTARTDATE', $data))
      $nvp_params['PROFILESTARTDATE']        = urlencode($data['PROFILESTARTDATE']);
    if (array_key_exists('PROFILEREFERENCE', $data))
      $nvp_params['PROFILEREFERENCE']        = urlencode($data['PROFILEREFERENCE']);
    if (array_key_exists('ADDITIONALBILLINGCYCLES', $data))
      $nvp_params['ADDITIONALBILLINGCYCLES'] = (int) $data['PROFILEREFERENCE'];
    if (array_key_exists('OUTSTANDINGAMT', $data))
      $nvp_params['OUTSTANDINGAMT']          = (float) $data['OUTSTANDINGAMT'];
    if (array_key_exists('DESC', $data) && strlen($data['DESC'] > 0))
      $nvp_params['DESC']                    = urlencode($data['DESC']);
    if (array_key_exists('MAXFAILEDPAYMENTS', $data) && (int) $data['MAXFAILEDPAYMENTS'] > 0)
      $nvp_params['MAXFAILEDPAYMENTS']       = (int) $data['PROFILEREFERENCE'];
    if (array_key_exists('AUTOBILLOUTAMT', $data))
      $nvp_params['AUTOBILLOUTAMT']          = $data['AUTOBILLOUTAMT'];
    if (array_key_exists('TOTALBILLINGCYCLES', $data))
      $nvp_params['TOTALBILLINGCYCLES']      = (int) $data['TOTALBILLINGCYCLES'];
    if (array_key_exists('AMT', $data))
      $nvp_params['AMT']                     = (float) $data['AMT'];
    else
      $_errors[]                             = 'AMT is required parameter';
    if (array_key_exists('TRIALBILLINGPERIOD', $data))
      $nvp_params['TRIALBILLINGPERIOD']      = urlencode($data['TRIALBILLINGPERIOD']);
    if (array_key_exists('TRIALBILLINGFREQUENCY', $data))
      $nvp_params['TRIALBILLINGFREQUENCY']   = (int) $data['TRIALBILLINGFREQUENCY'];
    if (array_key_exists('TRIALTOTALBILLINGCYCLES', $data))
      $nvp_params['TRIALTOTALBILLINGCYCLES'] = (int) $data['TRIALTOTALBILLINGCYCLES'];
    if (array_key_exists('TRIALAMT', $data))
      $nvp_params['TRIALAMT']                = (float) $data['TRIALAMT'];
    if (array_key_exists('CURRENCYCODE', $data))
      $nvp_params['CURRENCYCODE']            = urlencode($data['CURRENCYCODE']);
    else
      $_errors[]                             = 'CURRENCYCODE is required parameter';
    if (array_key_exists('SHIPPINGAMT', $data))
      $nvp_params['SHIPPINGAMT']             = (float) $data['SHIPPINGAMT'];
    if (array_key_exists('TAXAMT', $data))
      $nvp_params['TAXAMT']                  = (float) $data['TAXAMT'];

    //Actvation Details Fields
    if (array_key_exists('INITAMT', $data))
      $nvp_params['INITAMT']             = (float) $data['INITAMT'];
    if (array_key_exists('FAILEDINITAMTACTION', $data))
      $nvp_params['FAILEDINITAMTACTION'] = urlencode($data['FAILEDINITAMTACTION']);

    //Ship To Address Fields
    if (array_key_exists('SHIPTONAME', $data))
      $nvp_params['SHIPTONAME']     = urlencode($data['SHIPTONAME']);
    if (array_key_exists('SHIPTOSTREET', $data))
      $nvp_params['SHIPTOSTREET']   = urlencode($data['SHIPTOSTREET']);
    if (array_key_exists('SHIPTOSTREET2', $data))
      $nvp_params['SHIPTOSTREET2']  = urlencode($data['SHIPTOSTREET2']);
    if (array_key_exists('SHIPTOCITY', $data))
      $nvp_params['SHIPTOCITY']     = urlencode($data['SHIPTOCITY']);
    if (array_key_exists('SHIPTOSTATE', $data))
      $nvp_params['SHIPTOSTATE']    = urlencode($data['SHIPTOSTATE']);
    if (array_key_exists('SHIPTOZIP', $data))
      $nvp_params['SHIPTOZIP']      = urlencode($data['SHIPTOZIP']);
    if (array_key_exists('SHIPTOCOUNTRY', $data))
      $nvp_params['SHIPTOCOUNTRY']  = urlencode($data['SHIPTOCOUNTRY']);
    if (array_key_exists('SHIPTOPHONENUM', $data))
      $nvp_params['SHIPTOPHONENUM'] = urlencode($data['SHIPTOPHONENUM']);

    //Credit Card Details Fields
    if (array_key_exists('CREDITCARDTYPE', $data))
      $nvp_params['CREDITCARDTYPE'] = urlencode($data['CREDITCARDTYPE']);
    if (array_key_exists('ACCT', $data))
      $nvp_params['ACCT']           = urlencode($data['ACCT']);
    else
      $_errors[]                    = 'ACCT is required parameter';
    if (array_key_exists('EXPDATE', $data))
      $nvp_params['EXPDATE']        = urlencode($data['EXPDATE']);
    if (array_key_exists('CVV2', $data))
      $nvp_params['CVV2']           = urlencode($data['CVV2']);
    if (array_key_exists('STARTDATE', $data))
      $nvp_params['STARTDATE']      = urlencode($data['STARTDATE']);
    if (array_key_exists('ISSUENUMBER', $data))
      $nvp_params['ISSUENUMBER']    = urlencode($data['ISSUENUMBER']);

    //Payer Information Fields

    if (array_key_exists('EMAIL', $data))
      $nvp_params['EMAIL']      = urlencode($data['EMAIL']);
    if (array_key_exists('FIRSTNAME', $data))
      $nvp_params['FIRSTNAME']  = urlencode($data['FIRSTNAME']);
    if (array_key_exists('MIDDLENAME', $data))
      $nvp_params['MIDDLENAME'] = urlencode($data['MIDDLENAME']);
    if (array_key_exists('LASTNAME', $data))
      $nvp_params['LASTNAME']   = urlencode($data['LASTNAME']);
    if (array_key_exists('STREET', $data))
      $nvp_params['STREET']     = urlencode($data['STREET']);
    if (array_key_exists('STREET2', $data))
      $nvp_params['STREET2']    = urlencode($data['STREET2']);
    if (array_key_exists('CITY', $data))
      $nvp_params['CITY']       = urlencode($data['CITY']);
    if (array_key_exists('STATE', $data))
      $nvp_params['STATE']      = urlencode($data['STATE']);
    if (array_key_exists('ZIP', $data))
      $nvp_params['ZIP']        = urlencode($data['ZIP']);
    if (array_key_exists('COUNTRY', $data))
      $nvp_params['COUNTRY']    = urlencode($data['COUNTRY']);
    $request_param            = '';
    foreach ($nvp_params as $index => $value)
      $request_param.='&' . $index . '=' . $value;
    $result                   = $this->paypalHttpPostRequest('UpdateRecurringPaymentsProfile', $request_param);
    return $result;
  }

}
?>
