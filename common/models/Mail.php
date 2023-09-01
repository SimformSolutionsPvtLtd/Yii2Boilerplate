<?php

namespace common\models;

use Yii;

/**
 * Sending mail from common place.
 *
 */
class Mail extends \yii\db\ActiveRecord
{
    /**
     * Send mail.
     *
     * @return send mail
     */
    public function sendMail($to = "", $subject = "", $message = "", $mailData = [])
    {
        extract($mailData);
        $mailer = Yii::$app->mailer;
        $mail = $mailer->compose()
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setTo($to)
            ->setSubject($subject)
            ->setHtmlBody($message)
            ->send();

        return $mail;
    }
}
