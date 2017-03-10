<?php
/**
 * Created by PhpStorm.
 * User: vuong
 * Date: 11/1/2016
 * Time: 3:19 PM
 */

namespace App\Libraries\ZingSDK;

class ZME_FeedItem
{
    public $userIdFrom;
    public $userIdTo;
    public $actId;
    public $tplId;
    public $objectId;
    public $attachName;
    public $attachHref;
    public $attachCaption;
    public $attachDescription;
    public $mediaType;
    public $mediaImage;
    public $mediaSource;
    public $actionLinkText;
    public $actionLinkHref;

    public function ZME_FeedItem($userIdFrom, $userIdTo, $actId, $tplId, $objectId, $attachName, $attachHref, $attachCaption, $attachDescription, $mediaType, $mediaImage, $mediaSource, $actionLinkText, $actionLinkHref) {
        $this->userIdFrom = $userIdFrom;
        $this->userIdTo = $userIdTo;
        $this->actId = $actId;
        $this->tplId =$tplId;
        $this->objectId = $objectId;
        $this->attachName = $attachName;
        $this->attachHref = $attachHref;
        $this->attachCaption = $attachCaption;
        $this->attachDescription = $attachDescription;
        $this->mediaType = $mediaType;
        $this->mediaImage = $mediaImage;
        $this->mediaSource = $mediaSource;
        $this->actionLinkText = $actionLinkText;
        $this->actionLinkHref = $actionLinkHref;
    }
}