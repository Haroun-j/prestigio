import {testModule} from "../main/testModule";
import '../../styles/_base.scss';
import {initFavoriteToggle} from "../main/toggleFavourite";
import {pageLoader} from "../tools/pageLoader";
export function loadStorePageModules() {
    testModule();
    initFavoriteToggle();
}
