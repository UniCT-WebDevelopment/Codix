import { Resource } from '../resources/resource';
import { RESOURCES } from './resources.mock';

export class ResourceMock {

  getDir(id: any = 1): any{
    return {'data': RESOURCES[0]};
  }

}
