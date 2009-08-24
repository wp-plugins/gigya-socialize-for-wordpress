<?php 
/**
 * Encapsulates information about networks Gigya supports.
 */
class GigyaNetwork {

    /// NETWORK NAMES
    var $_network_AOL = 'aol';
    
    var $_network_Facebook = 'facebook';
    
    var $_network_Google = 'google';
    
    var $_network_MySpace = 'myspace';
    
    var $_network_Twitter = 'twitter';
    
    var $_network_Yahoo = 'yahoo';
    
    function canInviteFriends($providers) {
        $intersection = array_intersect($providers, $this->getInviteValidNetworks());
        return ! empty($intersection);
    }
    
    function canUpdateStatus($providers) {
        $intersection = array_intersect($providers, $this->getUpdateStatusValidNetworks());
        return ! empty($intersection);
    }
    
    /**
     * Returns the constant names of the networks with which you can send invites.
     *
     * @return array
     */
    function getInviteValidNetworks() {
        return array($this->_network_Facebook, $this->_network_Twitter);
    }
    
    /**
     * Returns the constant names of the networks with which you can perform a status update.
     *
     * @return array
     */
    function getUpdateStatusValidNetworks() {
        return array($this->_network_Facebook, $this->_network_MySpace, $this->_network_Twitter);
    }
}
?>