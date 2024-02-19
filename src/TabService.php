<?php

namespace Seblhaire\MenuAndTabUtils;

class TabService implements TabServiceContract {

    public function init($id, $data) {
        return new TabBuilder($id, $data);
    }
}
