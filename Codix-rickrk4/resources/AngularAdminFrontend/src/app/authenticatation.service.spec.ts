import { TestBed } from '@angular/core/testing';

import { AuthenticatationService } from './authenticatation.service';

describe('AuthenticatationService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: AuthenticatationService = TestBed.get(AuthenticatationService);
    expect(service).toBeTruthy();
  });
});
