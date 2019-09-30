import { Resource } from './resource';

export class Directory {
  id: number;
  title: string;
  coverUrl: string;
  url: string;
  type: string = 'directory';
}
