#!/usr/bin/env perl 
#===============================================================================
#
#         FILE: export.pl
#
#        USAGE: ./export.pl  
#
#  DESCRIPTION: export DNS zones using Cloudflare API
#
#      OPTIONS: ---
# REQUIREMENTS: CloudFlare.pm
#         BUGS: ---
#        NOTES: ---
#       AUTHOR: Viktor A. Manyushin
# ORGANIZATION: 
#      VERSION: 1.0
#      CREATED: 02/20/2015 12:53:57 AM
#     REVISION: 001
#===============================================================================
#
use strict;
use warnings;
use utf8;
use Data::Dumper;

use CloudFlare;

if ( ( $#ARGV + 1 ) != 1 ) {
    print "\nUsage: export.pl <zone>\n";
    exit;
}

my $cf = CloudFlare->new();

### set authorization token from https://www.cloudflare.com/my-account
$cf->api_token( '' );

### set your email address 
$cf->api_email( '' );

### zone name must pass from command line argument's
$cf->set_zone( $ARGV[0] );

my $response = $cf->rec_load_all();
exit if $response == 0;

$cf->print_header();
$cf->print_soa();

foreach my $rr ( @{$cf->get_content()} ) {
    my $pattern1 = qr/$ARGV[0]$/;

    if ( $rr->{'display_name'} eq $cf->get_zone())  { $rr->{'display_name'} = '@'; }
    if ( $rr->{'content'} =~ $pattern1 )   { $rr->{'content'} .= '.'; }

    if ( $rr->{'type'} eq 'MX')  {  
        printf( "%-25s %6s %6s %5s %15s\n", $rr->{'display_name'},'IN',$rr->{'type'},$rr->{'prio'},$rr->{'content'} );
    } 

    elsif ( $rr->{'type'} eq 'SPF' )  {  
        printf( "%-25s %6s %6s \"%-10s\"\n", $rr->{'display_name'},'IN',$rr->{'type'},$rr->{'content'} );
    }

    elsif ( $rr->{'type'} eq 'TXT' )  {  
        printf( "%-25s %6s %6s \"%-10s\"\n", $rr->{'display_name'},'IN',$rr->{'type'},$rr->{'content'} );
    }

    else {
        printf( "%-25s %6s %6s %15s\n", $rr->{'display_name'},'IN',$rr->{'type'},$rr->{'content'} );
    }
}
