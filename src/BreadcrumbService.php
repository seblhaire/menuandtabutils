<?php

namespace Seblhaire\MenuAndTabUtils;

class BreadcrumbService implements MenuServiceContract {

    public function init($id, $data) {
        return new BreadcrumbBuilder($id, $data);
    }
}
