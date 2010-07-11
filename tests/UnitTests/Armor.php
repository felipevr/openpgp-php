<?php
require_once(dirname(__FILE__).'/../../lib/openpgp.php');

class ArmorTest extends UnitTestCase {

    /**
     * @should unarmor the ASCII (RADIX-64) sample data file and discover that the first packet tag is proper 
     */
    public function testUnarmorKey() {
        $ascii = file_get_contents(dirname(__FILE__).'/../sampledata/fake_of.pubkey');
        $binary = OpenPGP::unarmor($ascii);
        $chars = unpack('C*', $binary);
        $octet = decbin($chars[1]);

        //first octet (old format) is 10yyyyzz
        //where 1 is new header, 0 means that this is not a new packet type,
        // yyyy is the packet type, zz is the packet length,
        $this->assertEqual(substr($octet, 0, 2),'10');
        $this->assertEqual(substr($octet, 2, 4),'0110');
        $this->assertEqual(substr($octet, 6, 2),'01');
    }
}
