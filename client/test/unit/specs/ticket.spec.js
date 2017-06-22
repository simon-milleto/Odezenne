import Vue from 'vue';
import Ticket from '../../../src/components/Ticket/Ticket';

describe('Ticket', () => {
  it('sets the correct default amount', () => {
    const defaultData = Ticket.data();
    expect(defaultData.amount).to.equal(1);
  });
});
