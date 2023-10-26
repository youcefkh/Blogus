import "./bootstrap";

import Alpine from "alpinejs";

import { LazyLoad, initTE } from "tw-elements";

initTE({ LazyLoad });

window.Alpine = Alpine;

Alpine.start();
