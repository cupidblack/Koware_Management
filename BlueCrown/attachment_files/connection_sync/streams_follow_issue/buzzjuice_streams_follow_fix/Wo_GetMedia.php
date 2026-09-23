function Wo_GetMedia($media)
{
    global $wo;

    /*
     * Deliberately side-effect-free.
     *
     * This function must never:
     * - start/destroy/regenerate a session;
     * - modify $_SESSION;
     * - set/delete cookies;
     * - call SSO code;
     * - access the database;
     * - redirect;
     * - write logs;
     * - perform filesystem access.
     */

    if ($media === null || $media === '') {
        return '';
    }

    if (!is_string($media)) {
        if (is_scalar($media)) {
            $media = (string)$media;
        } elseif (is_object($media) && method_exists($media, '__toString')) {
            $media = (string)$media;
        } else {
            return '';
        }
    }

    $media = trim($media);

    if ($media === '') {
        return '';
    }

    if (strpos($media, '//') === 0) {
        return $media;
    }

    if (
        stripos($media, 'http://') === 0 ||
        stripos($media, 'https://') === 0
    ) {
        return $media;
    }

    $config = array();

    if (isset($wo['config']) && is_array($wo['config'])) {
        $config = $wo['config'];
    }

    $site_url = '';

    if (isset($config['site_url']) && is_scalar($config['site_url'])) {
        $site_url = trim((string)$config['site_url']);
    }

    $media_path = ltrim($media, '/');

    if ($site_url === '') {
        return $media_path;
    }

    $site_url = rtrim($site_url, '/');

    $build_url = static function ($base, $path) {
        $base = rtrim((string)$base, '/');
        $path = ltrim((string)$path, '/');

        if ($base === '') {
            return $path;
        }

        if ($path === '') {
            return $base;
        }

        return $base . '/' . $path;
    };

    if (!empty($config['amazone_s3']) && (int)$config['amazone_s3'] === 1) {
        if (!empty($config['amazon_endpoint'])) {
            $endpoint = rtrim((string)$config['amazon_endpoint'], '/');

            if (filter_var($endpoint, FILTER_VALIDATE_URL)) {
                return $build_url($endpoint, $media_path);
            }
        }

        if (!empty($config['bucket_name']) && !empty($config['s3_site_url'])) {
            return $build_url($config['s3_site_url'], $media_path);
        }

        return $build_url($site_url, $media_path);
    }

    if (!empty($config['wasabi_storage']) && (int)$config['wasabi_storage'] === 1) {
        if (!empty($config['wasabi_endpoint'])) {
            $endpoint = rtrim((string)$config['wasabi_endpoint'], '/');

            if (filter_var($endpoint, FILTER_VALIDATE_URL)) {
                return $build_url($endpoint, $media_path);
            }
        }

        if (!empty($config['wasabi_bucket_name']) && !empty($config['wasabi_site_url'])) {
            return $build_url($config['wasabi_site_url'], $media_path);
        }

        return $build_url($site_url, $media_path);
    }

    if (!empty($config['spaces']) && (int)$config['spaces'] === 1) {
        if (!empty($config['spaces_endpoint'])) {
            $endpoint = rtrim((string)$config['spaces_endpoint'], '/');

            if (filter_var($endpoint, FILTER_VALIDATE_URL)) {
                return $build_url($endpoint, $media_path);
            }
        }

        if (!empty($config['space_name']) && !empty($config['space_region'])) {
            return 'https://' .
                rawurlencode((string)$config['space_name']) . '.' .
                rawurlencode((string)$config['space_region']) .
                '.digitaloceanspaces.com/' . $media_path;
        }

        return $build_url($site_url, $media_path);
    }

    if (!empty($config['ftp_upload']) && (int)$config['ftp_upload'] === 1) {
        $ftp_endpoint = !empty($config['ftp_endpoint'])
            ? trim((string)$config['ftp_endpoint'])
            : '';

        if ($ftp_endpoint !== '') {
            if (
                stripos($ftp_endpoint, 'http://') !== 0 &&
                stripos($ftp_endpoint, 'https://') !== 0 &&
                strpos($ftp_endpoint, '//') !== 0
            ) {
                $ftp_endpoint = 'https://' . ltrim($ftp_endpoint, '/');
            }

            return $build_url($ftp_endpoint, $media_path);
        }

        return $build_url($site_url, $media_path);
    }

    if (!empty($config['cloud_upload']) && (int)$config['cloud_upload'] === 1) {
        if (!empty($config['cloud_endpoint'])) {
            $endpoint = rtrim((string)$config['cloud_endpoint'], '/');

            if (filter_var($endpoint, FILTER_VALIDATE_URL)) {
                return $build_url($endpoint, $media_path);
            }
        }

        if (!empty($config['cloud_bucket_name'])) {
            return 'https://storage.googleapis.com/' .
                rawurlencode((string)$config['cloud_bucket_name']) .
                '/' . $media_path;
        }

        return $build_url($site_url, $media_path);
    }

    if (!empty($config['backblaze_storage']) && (int)$config['backblaze_storage'] === 1) {
        if (!empty($config['backblaze_endpoint'])) {
            $endpoint = rtrim((string)$config['backblaze_endpoint'], '/');

            if (filter_var($endpoint, FILTER_VALIDATE_URL)) {
                return $build_url($endpoint, $media_path);
            }
        }

        if (
            !empty($config['backblaze_bucket_name']) &&
            !empty($config['backblaze_bucket_region'])
        ) {
            return 'https://' .
                rawurlencode((string)$config['backblaze_bucket_name']) .
                '.s3.' .
                rawurlencode((string)$config['backblaze_bucket_region']) .
                '.backblazeb2.com/' .
                $media_path;
        }

        return $build_url($site_url, $media_path);
    }

    return $build_url($site_url, $media_path);
}
