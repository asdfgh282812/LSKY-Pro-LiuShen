<?php

namespace App\Enums;

final class ConfigKey
{
    /** @var string 是否啟用註冊 */
    const IsEnableRegistration = 'is_enable_registration';

    /** @var string 是否啟用畫廊 */
    const IsEnableGallery = 'is_enable_gallery';

    /** @var string 是否啟用API */
    const IsEnableApi = 'is_enable_api';

    /** @var string 程式名稱 */
    const AppName = 'app_name';

    /** @var string 程式版本 */
    const AppVersion = 'app_version';

    /** @var string 網站關鍵字 */
    const SiteKeywords = 'site_keywords';

    /** @var string 網站描述 */
    const SiteDescription = 'site_description';

    /** @var string 網站公告 */
    const SiteNotice = 'site_notice';

    /** @var string icp備案號 */
    const IcpNo = 'icp_no';

    /** @var string 是否允許訪客上傳 */
    const IsAllowGuestUpload = 'is_allow_guest_upload';

    /** @var string 使用者初始容量(kb) */
    const UserInitialCapacity = 'user_initial_capacity';

    /** @var string 帳戶是否需要驗證 */
    const IsUserNeedVerify = 'is_user_need_verify';

    /** @var string 郵件設定 */
    const Mail = 'mail';

    /** @var string 角色組預設設定 */
    const Group = 'group';

    /** @var string 頭像映象位址 */
    const AvatarMirrorUrl = 'avatar_mirror_url';

    /** @var string 自訂 CSS */
    const CustomCss = 'custom_css';

    /** @var string 自訂 JS */
    const CustomJs = 'custom_js';

    /** @var string 是否啟用 OAuth 登入 */
    const OauthEnable = 'oauth_enable';

    /** @var string 是否強制已綁定 SSO 的使用者只能透過 SSO 登入 */
    const OauthForceLogin = 'oauth_force_login';

    /** @var string 是否允許 OAuth 快速註冊 */
    const OauthAllowRegister = 'oauth_allow_register';

    /** @var string OAuth 登入展示名稱 */
    const OauthProviderName = 'oauth_provider_name';

    /** @var string OAuth 客戶端 ID */
    const OauthClientId = 'oauth_client_id';

    /** @var string OAuth 客戶端金鑰 */
    const OauthClientSecret = 'oauth_client_secret';

    /** @var string OAuth 授權位址 */
    const OauthAuthorizeUrl = 'oauth_authorize_url';

    /** @var string OAuth Token 位址 */
    const OauthTokenUrl = 'oauth_token_url';

    /** @var string OAuth 使用者資訊位址 */
    const OauthUserinfoUrl = 'oauth_userinfo_url';

    /** @var string OAuth Scope */
    const OauthScope = 'oauth_scope';

    /** @var string OAuth 唯一標識欄位 */
    const OauthUserIdField = 'oauth_user_id_field';

    /** @var string OAuth 使用者名稱欄位 */
    const OauthUserNameField = 'oauth_user_name_field';

    /** @var string OAuth 電子郵件欄位 */
    const OauthUserEmailField = 'oauth_user_email_field';

    /** @var string 是否啟用 OAuth PKCE */
    const OauthPkceEnable = 'oauth_pkce_enable';
}
