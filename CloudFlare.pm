#
#===============================================================================
#
#         FILE: CloudFlare.pm
#
#  DESCRIPTION: CloudFlare mudule provides API access
#
#        FILES: CloudFlare.pm
#         BUGS: ---
#        NOTES: ---
#       AUTHOR: Viktar A. Manyushin 
# ORGANIZATION: 
#      VERSION: 1.0
#      CREATED: 02/20/2015 03:24:09 AM
#     REVISION: ---
#===============================================================================
package CloudFlare;

use strict;
use warnings;
use LWP::UserAgent;
use JSON;
use Net::DNS;

sub new {
    my $class = shift;
    my $self  = {};
    
    $self->{'api_url'} = 'https://www.cloudflare.com/api_json.html';
    $self->{'ua'}      = LWP::UserAgent->new( ssl_opts => { verify_hostname => 0 } );

    return bless $self , $class;
}

sub api_token {
    my $self = shift;
    $self->{'api_token'} = shift;
}

sub api_email {
    my $self = shift;
    $self->{'api_email'} = shift;
}

sub set_zone {
    my $self = shift;
    $self->{'zone'} = shift;
}

sub get_zone {
    shift->{'zone'};
}

sub rec_load_all {
    my $self = shift;

    $self->{'api_req'} = { 
        'a'     => 'rec_load_all', 
        'tkn'   => $self->{'api_token'}, 
        'email' => $self->{'api_email'}, 
        'z'     => $self->{'zone'}
    };    

    my $response = $self->{'ua'}->post($self->{'api_url'}, $self->{'api_req'});
    
    if ($response->is_success) {
        $self->{'response'} = decode_json($response->decoded_content);
    } else {
        print "error while request Cloudflare API params: $self->{'api_req'}\n";
        return 0;
    }

    __get_soa_rr($self);

    return 1;
}

sub get_content {
    my $self = shift;
    my $data = $self->{'response'};
    return $data->{'response'}{'recs'}{'objs'};
}

sub __get_soa_rr {
    my $self     = shift;
    my $resolver = new Net::DNS::Resolver();
    my $reply    = $resolver->query( $self->{'zone'}, 'SOA' );
    
    if ( $reply ) {
        $self->{'soa'} = ($reply->answer)[0];
    }
}

sub print_header {
    my $self = shift;

    print ";;\n";
    print ";; " . localtime() . "\n";
    print ";; zone " . $self->{'zone'} . "\n";
    print ";;\n\n";
}

sub print_soa {
    my $self = shift;

    print "\$ORIGIN " . $self->{'zone'} . ".\n";
    $self->{'soa'}->print;
}

1;
