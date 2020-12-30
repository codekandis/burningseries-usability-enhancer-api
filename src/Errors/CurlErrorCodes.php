<?php declare( strict_types = 1 );
namespace CodeKandis\BurningSeriesUsabilityEnhancerApi\Errors;

abstract class CurlErrorCodes
{
	public const CURLE_UNSUPPORTED_PROTOCOL     = 1;

	public const CURLE_FAILED_INIT              = 2;

	public const CURLE_URL_MALFORMAT            = 3;

	public const CURLE_NOT_BUILT_IN             = 4;

	public const CURLE_COULDNT_RESOLVE_PROXY    = 5;

	public const CURLE_COULDNT_RESOLVE_HOST     = 6;

	public const CURLE_COULDNT_CONNECT          = 7;

	public const CURLE_WEIRD_SERVER_REPLY       = 8;

	public const CURLE_REMOTE_ACCESS_DENIED     = 9;

	public const CURLE_FTP_ACCEPT_FAILED        = 10;

	public const CURLE_FTP_WEIRD_PASS_REPLY     = 11;

	public const CURLE_FTP_ACCEPT_TIMEOUT       = 12;

	public const CURLE_FTP_WEIRD_PASV_REPLY     = 13;

	public const CURLE_FTP_WEIRD_227_FORMAT     = 14;

	public const CURLE_FTP_CANT_GET_HOST        = 15;

	public const CURLE_HTTP2                    = 16;

	public const CURLE_FTP_COULDNT_SET_TYPE     = 17;

	public const CURLE_PARTIAL_FILE             = 18;

	public const CURLE_FTP_COULDNT_RETR_FILE    = 19;

	public const CURLE_QUOTE_ERROR              = 21;

	public const CURLE_HTTP_RETURNED_ERROR      = 22;

	public const CURLE_WRITE_ERROR              = 23;

	public const CURLE_UPLOAD_FAILED            = 25;

	public const CURLE_READ_ERROR               = 26;

	public const CURLE_OUT_OF_MEMORY            = 27;

	public const CURLE_OPERATION_TIMEDOUT       = 28;

	public const CURLE_FTP_PORT_FAILED          = 30;

	public const CURLE_FTP_COULDNT_USE_REST     = 31;

	public const CURLE_RANGE_ERROR              = 33;

	public const CURLE_HTTP_POST_ERROR          = 34;

	public const CURLE_SSL_CONNECT_ERROR        = 35;

	public const CURLE_BAD_DOWNLOAD_RESUME      = 36;

	public const CURLE_FILE_COULDNT_READ_FILE   = 37;

	public const CURLE_LDAP_CANNOT_BIND         = 38;

	public const CURLE_LDAP_SEARCH_FAILED       = 39;

	public const CURLE_FUNCTION_NOT_FOUND       = 41;

	public const CURLE_ABORTED_BY_CALLBACK      = 42;

	public const CURLE_BAD_FUNCTION_ARGUMENT    = 43;

	public const CURLE_INTERFACE_FAILED         = 45;

	public const CURLE_TOO_MANY_REDIRECTS       = 47;

	public const CURLE_UNKNOWN_OPTION           = 48;

	public const CURLE_TELNET_OPTION_SYNTAX     = 49;

	public const CURLE_GOT_NOTHING              = 52;

	public const CURLE_SSL_ENGINE_NOTFOUND      = 53;

	public const CURLE_SSL_ENGINE_SETFAILED     = 54;

	public const CURLE_SEND_ERROR               = 55;

	public const CURLE_RECV_ERROR               = 56;

	public const CURLE_SSL_CERTPROBLEM          = 58;

	public const CURLE_SSL_CIPHER               = 59;

	public const CURLE_PEER_FAILED_VERIFICATION = 60;

	public const CURLE_BAD_CONTENT_ENCODING     = 61;

	public const CURLE_LDAP_INVALID_URL         = 62;

	public const CURLE_FILESIZE_EXCEEDED        = 63;

	public const CURLE_USE_SSL_FAILED           = 64;

	public const CURLE_SEND_FAIL_REWIND         = 65;

	public const CURLE_SSL_ENGINE_INITFAILED    = 66;

	public const CURLE_LOGIN_DENIED             = 67;

	public const CURLE_TFTP_NOTFOUND            = 68;

	public const CURLE_TFTP_PERM                = 69;

	public const CURLE_REMOTE_DISK_FULL         = 70;

	public const CURLE_TFTP_ILLEGAL             = 71;

	public const CURLE_TFTP_UNKNOWNID           = 72;

	public const CURLE_REMOTE_FILE_EXISTS       = 73;

	public const CURLE_TFTP_NOSUCHUSER          = 74;

	public const CURLE_CONV_FAILED              = 75;

	public const CURLE_CONV_REQD                = 76;

	public const CURLE_SSL_CACERT_BADFILE       = 77;

	public const CURLE_REMOTE_FILE_NOT_FOUND    = 78;

	public const CURLE_SSH                      = 79;

	public const CURLE_SSL_SHUTDOWN_FAILED      = 80;

	public const CURLE_AGAIN                    = 81;

	public const CURLE_SSL_CRL_BADFILE          = 82;

	public const CURLE_SSL_ISSUER_ERROR         = 83;

	public const CURLE_FTP_PRET_FAILED          = 84;

	public const CURLE_RTSP_CSEQ_ERROR          = 85;

	public const CURLE_RTSP_SESSION_ERROR       = 86;

	public const CURLE_FTP_BAD_FILE_LIST        = 87;

	public const CURLE_CHUNK_FAILED             = 88;

	public const CURLE_NO_CONNECTION_AVAILABLE  = 89;

	public const CURLE_SSL_PINNEDPUBKEYNOTMATCH = 90;

	public const CURLE_SSL_INVALIDCERTSTATUS    = 91;

	public const CURLE_HTTP2_STREAM             = 92;

	public const CURLE_RECURSIVE_API_CALL       = 93;

	public const CURLE_AUTH_ERROR               = 94;

	public const CURLE_HTTP3                    = 95;

	public const CURLE_QUIC_CONNECT_ERROR       = 96;
}
