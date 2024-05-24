<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://dev.ilyasine.com/
 * @since      1.0.0
 *
 * @package    Ikacom_Api
 * @subpackage Ikacom_Api/public/partials
 */


 $image_src = "https://www.ikacom.fr/cartouche/sva/08/1/$sva/A300";
 $wallet_bal = get_user_meta( get_current_user_id(), 'wps_wallet', true );
 $wallet_bal = empty( $wallet_bal ) ? 0 : $wallet_bal;

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->


<div id="page" class="">
   <div class="title-bar">
      <div class="pivot-left">
         <h1><?php echo $code; ?> fois <strong>pour obtenir un code d'accès <?php echo wc_price($tarif); ?></strong></h1>
      </div>
      <div class="pivot-right">
         <p id="secure-payment" style="background: url('<?php echo IKACOM_ICON; ?>secure-lock.gif') right center no-repeat;">Paiement sécurisé</p>
      </div>
   </div>
   <div id="content">
      <div id="worldwide">
         <div class="layout">
            <div class="country disabled">
               <img class="selectDialog" src="<?php echo IKACOM_ICON . $region ?>.png" alt="France" height="24" width="24">
               <span class="selectDialog"><?php echo esc_html($country_label)?></span>
               <div class="multilanguages">
               </div>
            </div>
         </div>
      </div>
      <h2 id="payment-step-1" class="payment-step">
         <span id="step-number-1" class="step-number">1</span>
         <div id="step-number-1-text">Pour obtenir un code d'accès rapidement, <span>suivez les instructions</span></div>
      </h2>
      <div class="payment-instruction-block pricepoint-512 pricepoint-premium-sms-fr pricepoint-fr">
         <div class="message-detail">
            <strong>Pour recevoir votre code <div class="keyword">Appelez ce numero <b class="bold"><?php echo $code; ?></b> fois </strong>
         </div>
         <img src="<?php echo $image_src; ?>" alt="<?php echo $sva ?>" />
      </div>
      <div class="clearer">&nbsp;</div>
      <h2 id="payment-step-2" class="payment-step">
         <span id="step-number-2" class="step-number">2</span>
         Entrez votre code d'accès <span>pour obtenir votre achat</span>    
      </h2>
      <div class="payment-instruction-block access-codes">
         <div class="input-container">
            <input class="access-code-input" 
                     id="access-code-input" 
                     type="number" 
                     size="8" 
                     maxlength="8"
                     step="0.50"
                     data-sva="<?php echo esc_attr($sva); ?>"
                     data-code_formule="<?php echo esc_attr($code_formule);?>"
                     data-wallet_bal="<?php echo esc_attr($wallet_bal);?>"
                     data-tarif="<?php echo esc_attr($tarif);?>"                    
            >
            <div class="icon-container">
                  <img src="<?php echo IKACOM_ICON ?>paste-icon.svg" alt="Paste Icon">
            </div>
         </div>
         <div class="validation-block">
            <div class="result-message"></div>
            <button id="ikacom-access-code-validation" class="ikacom-button">Valider</button>
         </div>
      </div>

   </div>
   <div class="copyright-container">
      <div id="copyright" style="background: url('<?php echo IKACOM_ICON; ?>logo-ikacom.png') top center / 100px 100% no-repeat;"></div>
      <p>Service édité par Ikacom Copyright © <?php echo date("Y"); ?> Ikacom Inc. Tous droits réservés.</p>
   </div>

</div>

