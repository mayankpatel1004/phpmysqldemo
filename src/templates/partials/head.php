<?php
$arrMetaDetails = getMetaDetails();
$metaTitle = $arrMetaDetails['metaTitle'] ?? "Default Title";
$metaDescription = $arrMetaDetails['metaDescription'] ?? "Default Meta Description";
$pageTitle = $arrMetaDetails['pageTitle'] ?? "Default Page Title";
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $metaDescription; ?>" />
    <meta property="og:title" content="<?php echo $metaTitle; ?>" />
    <meta property="og:description" content="<?php echo $metaDescription; ?>" />
    <meta property="og:image" content="./public/images/default_profile_photo.png" />
    <meta property="og:url" content="https://example.com/products" />
    <meta name="twitter:card" content="summary_large_image" />
    <link rel="canonical" href="https://example.com/products/dry-fruits" />
    <!-- base:css -->
    <link rel="stylesheet" href="./public/assets/vendors/mdi/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="./public/assets/vendors/css/vendor.bundle.base.css" />
    <link rel="stylesheet" href="./public/assets/vendors/flag-icon-css/css/flag-icon.min.css" />
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="./public/assets/css/vertical-layout-light/style.css" />
    <link rel="stylesheet" href="./public/assets/css/vertical-layout-light/custom.css" />
    
    <link rel="stylesheet" href="./public/assets/vendors/select2/select2.min.css" />
    <link rel="stylesheet" href="./public/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css" />

    <!-- endinject -->
    <link rel="shortcut icon" href="./public/assets/images/favicon.png" />
</head>